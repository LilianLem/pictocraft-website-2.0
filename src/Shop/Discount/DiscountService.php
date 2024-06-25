<?php

namespace App\Shop\Discount;

use App\Entity\Core\User\User;
use App\Entity\Shop\Discount\AppliedDiscount;
use App\Entity\Shop\Discount\Discount;
use App\Entity\Shop\Discount\DiscountAppliesOnEnum;
use App\Entity\Shop\DiscountableEntityInterface;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\Product;
use App\Repository\Shop\Discount\DiscountRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DiscountService
{
    private DiscountRepository $discountRepository;
    private EntityManagerInterface $em;

    public function __construct(DiscountRepository $discountRepository, EntityManagerInterface $em)
    {
        $this->discountRepository = $discountRepository;
        $this->em = $em;
    }

    /** @return Discount[] */
    public function getEligibleDiscountsForOrder(Order $order, ?bool $appliedAutomatically = true): array
    {
        $availableDiscounts = $this->discountRepository->findAvailable($appliedAutomatically);
        return array_filter($availableDiscounts, fn(Discount $discount) => $discount->isOrderEligible($order));
    }

    /** @return Discount[] */
    private function getEligibleDiscountsForProductPricing(Product $product, ?User $user = null): array
    {
        $availableDiscounts = $this->discountRepository->findAvailable(true, DiscountAppliesOnEnum::ALL_ELIGIBLE_PRODUCTS);
        return array_filter($availableDiscounts, fn(Discount $discount) => $discount->isProductEligible($product, $user));
    }

    /** @return Discount[] */
    private function getFilteredEligibleDiscountsForProductPricing(Product $product, ?User $user = null): array
    {
        $eligibleDiscounts = $this->getEligibleDiscountsForProductPricing($product, $user);

        if(empty($eligibleDiscounts)) {
            return $eligibleDiscounts;
        }

        $eligibleDiscounts = $this->sort($eligibleDiscounts);

        /** @var array<int, Discount> $filteredEligibleDiscounts */
        $filteredEligibleDiscounts = [];

        foreach($eligibleDiscounts as $discount) {
            $incompatibleDiscounts = $discount->getIncompatibleDiscounts();
            if(!empty($incompatibleDiscounts)) {
                $valid = true;
                foreach($incompatibleDiscounts as $iDiscount) {
                    if(array_key_exists($iDiscount->getId(), $filteredEligibleDiscounts)) {
                        $valid = false;
                        break;
                    }
                }

                if($valid) {
                    $filteredEligibleDiscounts[$discount->getId()] = $discount;
                }
            }
        }

        return array_values($filteredEligibleDiscounts);
    }

    /** @return array<int, Discount> */
    public function getDiscountsApplied(DiscountableEntityInterface $discountableEntity, ?bool $includeItemsDiscounts = null): array
    {
        $entityIsOrder = get_class($discountableEntity) === "App\Entity\Shop\Order\Order";

        if(!$entityIsOrder && $includeItemsDiscounts) {
            throw new Exception("Impossible d'inclure les réductions des articles de la commande car l'entité fournie est un article");
        }

        /** @var Discount[] $discounts */
        $discounts = [];

        /** @var ReadableCollection<int, Discount> $discounts */
        $entityDiscounts = $discountableEntity->getAppliedDiscounts()->map(fn(AppliedDiscount $appliedDiscount) => $appliedDiscount->getDiscount());

        /** @var Discount $discount */
        foreach($entityDiscounts as $discount) {
            $discounts[$discount->getId()] = $discount;
        }

        if($includeItemsDiscounts) {
            /** @var Order $discountableEntity */
            foreach($discountableEntity->getItems() as $item) {
                $itemDiscounts = $item->getAppliedDiscounts()
                    ->map(fn(AppliedDiscount $appliedDiscount) => $appliedDiscount->getDiscount())
                    ->filter(fn(Discount $discount) => !array_key_exists($discount->getId(), $discounts))
                ;

                foreach($itemDiscounts as $discount) {
                    $discounts[$discount->getId()] = $discount;
                }
            }
        }

        return $discounts;
    }

    public function getDiscountByCode(string $code): ?Discount
    {
        return $this->discountRepository->findOneBy(["code" => $code]);
    }

    /**
     * Sort first by priority (highest first, null last), if equal by discount mode
     * (on most expensive item, then on cheapest item, then on all eligible items,
     * then on order), if equal by fixed discount (highest first, null last), if equal
     * by percentage discount (highest first, null last) and if equal by id (lowest first)
     *
     * @param Discount[] $discounts
     * @return Discount[]
     */
    private function rawSort(array $discounts): array
    {
        usort(
            $discounts,
            fn(Discount $a, Discount $b) => $a->getPriority() === $b->getPriority()
                ? ($a->getAppliesOn()->value === $b->getAppliesOn()->value
                    ? ($a->getFixedDiscount() === $b->getFixedDiscount()
                        ? ($a->getPercentageDiscount() === $b->getPercentageDiscount()
                            ? ($a->getId() === $b->getId()
                                ? 0
                                : ($a->getId() < $b->getId() ? -1 : 1)
                            )
                            : ($a->getPercentageDiscount() > $b->getPercentageDiscount() ? -1 : 1)
                        )
                        : ($a->getFixedDiscount() > $b->getFixedDiscount() ? -1 : 1)
                    )
                    : ($a->getAppliesOn()->value > $b->getAppliesOn()->value ? -1 : 1)
                )
                : ($a->getPriority() > $b->getPriority() ? -1 : 1)
        );

        return $discounts;
    }

    /**
     * Sort order discounts first then item discounts, then order by priority (highest first, null last),
     * if equal by discount mode (on most expensive item, then on cheapest item, then on all eligible
     * items, then on order), if equal by fixed discount (highest first, null last), if equal by
     * percentage discount (highest first, null last) and if equal by id (lowest first)
     *
     * @param Discount[] $discounts
     * @return Discount[]
     */
    public function sort(array $discounts): array
    {
        $discountsApplyingOnOrder = array_filter($discounts, fn(Discount $discount) => $discount->getAppliesOn() === DiscountAppliesOnEnum::ORDER);
        $discountsApplyingOnItems = array_udiff($discounts, $discountsApplyingOnOrder, fn(Discount $a, Discount $b) => $a->getId() - $b->getId());

        return array_merge($this->rawSort($discountsApplyingOnItems), $this->rawSort($discountsApplyingOnOrder));
    }

    /**
     * Warning: this reevaluates conflicts and recalculates amounts of all already applied discounts
     * @param Discount[] $discounts
     * @return array{
     *     success: bool,
     *     message: string,
     *     applied: Discount[],
     *     notApplied: array{
     *        array{
     *           discount: Discount,
     *           message: string
     *        }
     *     }
     *  }
     */
    public function applyMultipleOnOrder(
        array $discounts,
        Order $order,
        bool $removeAlreadyAppliedDiscounts = false,
        bool $cancelEverythingIfOneCriticalDiscountFailsToApply = false,
        bool $ignoreAvailabilityChecksOnPreviouslyAppliedDiscounts = false,
        bool $ignoreAvailabilityChecksOnAllDiscounts = false,
        ?DateTimeInterface $date = null
    ): array {
        // Already applied discounts that are also in $discounts parameter are considered as new in this array
        /** @var array{
         *     previouslyApplied: int[],
         *     new: int[]
         *  } $discountIdsPerType
         */
        $discountIdsPerType = [
            "previouslyApplied" => [],
            "new" => array_map(fn(Discount $discount) => $discount->getId(), $discounts)
        ];

        $currentDiscounts = $this->getDiscountsApplied($order, true);

        if(!$removeAlreadyAppliedDiscounts && $currentDiscounts) {
            foreach($currentDiscounts as $currentDiscount) {
                if(!in_array($currentDiscount->getId(), $discountIdsPerType["new"])) {
                    $discountIdsPerType["previouslyApplied"] = $currentDiscount->getId();
                    $discounts[] = $currentDiscount;
                }
            }
        }

        $order->removeAllAppliedDiscounts(true);

        $discounts = $this->sort($discounts);

        $orderDiscountsCache = new OrderDiscountsCache();

        /** @var array{
         *     success: bool,
         *     message: string,
         *     applied: array<int, Discount>,
         *     notApplied: array{
         *        array{
         *           discount: Discount,
         *           message: string
         *        }
         *     }
         *  } $result
         */
        $result = [
            "success" => false,
            "message" => "",
            "applied" => [],
            "notApplied" => []
        ];

        foreach($discounts as $discount) {
            $applyDiscountResult = $this->applyOnOrder(
                $discount,
                $order,
                $orderDiscountsCache,
                $result["applied"],
                $ignoreAvailabilityChecksOnAllDiscounts ? true : ($ignoreAvailabilityChecksOnPreviouslyAppliedDiscounts ? isset($discountIdsPerType["previouslyApplied"][$discount->getId()]) : false),
                $date
            );

            if($applyDiscountResult->applied) {
                $result["applied"][$discount->getId()] = $discount;
            } elseif($cancelEverythingIfOneCriticalDiscountFailsToApply && $applyDiscountResult->notAppliedType === NotAppliedTypeEnum::CRITICAL) {
                $result["message"] = "Aucune réduction n'a été appliquée en raison d'une erreur critique sur l'une des réductions (articles inéligibles ou réduction indisponible).";
                $result["applied"] = [];
                $result["notApplied"] = [];

                if(empty($currentDiscounts)) {
                    $order->removeAllAppliedDiscounts(true);
                } else {
                    $this->applyMultipleOnOrder($currentDiscounts, $order, true, ignoreAvailabilityChecksOnAllDiscounts: true);
                    $result["message"] .= " Les réductions anciennement appliquées ont été restaurées.";
                }

                return $result;
            } else {
                $result["notApplied"][] = [
                    "discount" => $discount,
                    "message" => $applyDiscountResult->notAppliedMessage
                ];
            }

            $orderDiscountsCache = $applyDiscountResult->orderDiscountsCache;
        }

        $result["success"] = !empty($result["applied"]);
        $result["message"] = $result["success"] ? (empty($result["notApplied"]) ? "Toutes les " : "Certaines ")."réductions ont été appliquées !" : "Aucune réduction n'a pu être appliquée.";

        return $result;
    }

    /**
     *  Warning: never use this function directly if other discounts are already applied on Order or one of its items, because of priority reasons affecting amounts calculation!
     *  Always prefer using applyMultipleOnOrder(). Only exception is if it's 100% sure the discount will be the very last one applied.
     *
     *  @param array<int, Discount> $alreadyAppliedDiscounts
     */
    private function applyOnOrder(
        Discount            $discount,
        Order               $order,
        OrderDiscountsCache $discountsCache,
        ?array              $alreadyAppliedDiscounts = null,
        bool                $ignoreAvailabilityChecks = false,
        ?DateTimeInterface  $date = null
    ): ApplyDiscountReturnType {
        if(!$ignoreAvailabilityChecks && !$discount->isAvailable($date)) {
            return new ApplyDiscountReturnType(
                false,
                $discountsCache,
                NotAppliedTypeEnum::CRITICAL,
                "Cette réduction n'est pas disponible"
            );
        }

        if($discount->getMaxUsesPerUser()) {
            if(!$order->getUser()) {
                return new ApplyDiscountReturnType(
                    false,
                    $discountsCache,
                    NotAppliedTypeEnum::CRITICAL,
                    "Cette réduction est réservée aux utilisateurs connectés"
                );
            } elseif(!$ignoreAvailabilityChecks && $discount->hasMaxUsesBeenReached($order->getUser())) {
                return new ApplyDiscountReturnType(
                    false,
                    $discountsCache,
                    NotAppliedTypeEnum::CRITICAL,
                    "Le nombre maximal d'utilisations a été atteint pour ce compte"
                );
            }
        }

        if($discount->getIncompatibleDiscounts()) {
            $incompatibleDiscounts = $discount->getIncompatibleDiscounts();

            if(is_null($alreadyAppliedDiscounts)) {
                $alreadyAppliedDiscounts = $this->getDiscountsApplied($order, true);
            }

            /** @var array<int, Discount> $conflictedDiscounts */
            $conflictedDiscounts = [];

            foreach($alreadyAppliedDiscounts as $id => $discount) {
                if(isset($incompatibleDiscounts[$id])) {
                    $conflictedDiscounts[$id] = $discount;
                }
            }

            if($conflictedDiscounts) {
                return new ApplyDiscountReturnType(
                    false,
                    $discountsCache,
                    NotAppliedTypeEnum::CRITICAL,
                    "Cette réduction est incompatible avec les réductions suivantes : ".implode(", ", array_map(fn(Discount $discount):string => empty($discount->getLabel()) ? $discount->getId() : $discount->getLabel(), $conflictedDiscounts))
                );
            }
        }

        if($discount->getAppliesOn() === DiscountAppliesOnEnum::ORDER) {
            if(!$order->getTotalAmountTtc()) {
                return new ApplyDiscountReturnType(
                    false,
                    $discountsCache,
                    NotAppliedTypeEnum::SAFE,
                    "La commande est déjà gratuite"
                );
            }

            if($order->getAppliedDiscounts()->exists(fn(int $key, AppliedDiscount $appliedDiscount) => $appliedDiscount->getDiscount()->getId() === $discount->getId())) {
                return new ApplyDiscountReturnType(
                    false,
                    $discountsCache,
                    NotAppliedTypeEnum::SAFE,
                    "Cette réduction a déjà été appliquée"
                );
            }

            $discountAmount = $this->calculateOrderDiscountAmount($discount, $order, $discountsCache);

            if(!$discountAmount) {
                return new ApplyDiscountReturnType(
                    false,
                    $discountsCache,
                    NotAppliedTypeEnum::SAFE,
                    "Le montant calculé de la réduction est nul"
                );
            }

            $this->addAppliedDiscountOnEntity($order, $discount, $discountAmount);
        } else {
            /** @var OrderItem[] $eligibleItems */
            $eligibleItems = $discount->getConstraintGroups()->isEmpty() ? $order->getItems() : $discount->getEligibleItemsInOrder($order, $date)->toArray();

            if(!$eligibleItems) {
                return new ApplyDiscountReturnType(
                    false,
                    $discountsCache,
                    NotAppliedTypeEnum::CRITICAL,
                    "Il n'y a aucun article éligible dans la commande"
                );
            }

            // Order by product price from lowest to highest
            usort($eligibleItems, fn(OrderItem $a, OrderItem $b) => $a->getBasePriceTtcPerUnit() === $b->getBasePriceTtcPerUnit() ? 0 : ($a->getBasePriceTtcPerUnit() < $b->getBasePriceTtcPerUnit() ? -1 : 1));

            if($discount->getAppliesOn() !== DiscountAppliesOnEnum::ALL_ELIGIBLE_PRODUCTS) {
                $itemToDiscount = $discount->getAppliesOn() === DiscountAppliesOnEnum::CHEAPEST_ELIGIBLE_PRODUCT ? $eligibleItems[0] : $eligibleItems[array_key_last($eligibleItems)];

                if($itemToDiscount->getAppliedDiscounts()->exists(fn(int $key, AppliedDiscount $appliedDiscount) => $appliedDiscount->getDiscount()->getId() === $discount->getId())) {
                    return new ApplyDiscountReturnType(
                        false,
                        $discountsCache,
                        NotAppliedTypeEnum::SAFE,
                        "Cette réduction est déjà appliquée sur l'article concerné"
                    );
                }

                $discountCalculation = $this->calculateOrderItemDiscountAmount($discount, $itemToDiscount, $discountsCache->getItemCache($itemToDiscount->getProduct()), 0, $discount->getMaxEligibleItemQuantityInCart());

                if(!$discountCalculation["discountAmount"]) {
                    return new ApplyDiscountReturnType(
                        false,
                        $discountsCache,
                        NotAppliedTypeEnum::SAFE,
                        "Le montant calculé de la réduction est nul"
                    );
                }

                $discountsCache->setItemCache($itemToDiscount->getProduct(), $discountCalculation["itemDiscountsCache"]);

                $this->addAppliedDiscountOnEntity($itemToDiscount, $discount, $discountCalculation["discountAmount"]);
                $itemToDiscount->updateTotalAmountTtc();
            } else {
                $discountAlreadyAppliedAmount = 0;
                $discountRemainingEligibleItemQuantity = $discount->getMaxEligibleItemQuantityInCart();
                $discountNotAppliedOnItemsNb = 0;

                foreach($eligibleItems as $item) {
                    if($item->getAppliedDiscounts()->exists(fn(int $key, AppliedDiscount $appliedDiscount) => $appliedDiscount->getDiscount()->getId() === $discount->getId())) {
                        $discountNotAppliedOnItemsNb++;
                        continue;
                    }

                    $discountCalculation = $this->calculateOrderItemDiscountAmount($discount, $item, $discountsCache->getItemCache($item->getProduct()), $discountAlreadyAppliedAmount, $discountRemainingEligibleItemQuantity);

                    // Create or overwrite variables based on key/values extracted from array
                    /**
                     *  @var int $discountAmount
                     *  @var OrderItemDiscountsCache $itemDiscountsCache
                     *  @var int $discountAlreadyAppliedAmount
                     *  @var int|null $discountRemainingEligibleItemQuantity
                     */
                    extract($discountCalculation, EXTR_OVERWRITE);

                    if(!$discountAmount) {
                        $discountNotAppliedOnItemsNb++;
                        continue;
                    }

                    $discountsCache->setItemCache($item->getProduct(), $itemDiscountsCache);

                    $this->addAppliedDiscountOnEntity($item, $discount, $discountAmount);
                    $item->updateTotalAmountTtc();
                }

                if($discountNotAppliedOnItemsNb === count($eligibleItems)) {
                    return new ApplyDiscountReturnType(
                        false,
                        $discountsCache,
                        NotAppliedTypeEnum::SAFE,
                        "La réduction n'est pas applicable (déjà appliquée ou montant nul)"
                    );
                }
            }
        }

        $order->updateTotals();

        return new ApplyDiscountReturnType(true, $discountsCache);
    }

    private function addAppliedDiscountOnEntity(DiscountableEntityInterface $discountableEntity, Discount $discount, int $discountAmount): void
    {
        $appliedDiscount = (new AppliedDiscount())->setDiscount($discount)
            ->setAmount($discountAmount)
            ->setFixedDiscount($discount->getFixedDiscount())
            ->setPercentageDiscount($discount->getPercentageDiscount())
            ->setConditions($discount->getConditions());

        $discountableEntity->addAppliedDiscount($appliedDiscount);
        $this->em->persist($appliedDiscount);
    }

    private function calculateOrderDiscountAmount(Discount $discount, Order $order, OrderDiscountsCache $discountsCache): int
    {
        if($discount->getAppliesOn() !== DiscountAppliesOnEnum::ORDER) {
            throw new Exception("Impossible de calculer le montant de la réduction. Veuillez contacter l'administrateur");
        }

        if(!$order->getTotalAmountTtc()) return 0;

        $discountAmount = $this->calculateDiscountRawAmount($discount, $discountsCache->getOrderDiscountCalculationBasis() ?? $order->getTotalAmountTtcBeforeOrderDiscounts());

        if($discountAmount > $order->getTotalAmountTtc()) {
            $discountAmount = $order->getTotalAmountTtc();
        }

        if($discountAmount > 0 && !$discount->getPercentageDiscount()) {
            $discountsCache->setOrderDiscountCalculationBasis($discountAmount);
        }

        return $discountAmount;
    }

    /** @return array{
     *     discountAmount: int,
     *     itemDiscountsCache: OrderItemDiscountsCache,
     *     discountAlreadyAppliedAmount: int,
     *     discountRemainingEligibleItemQuantity: int|null
     *  }
     */
    private function calculateOrderItemDiscountAmount(Discount $discount, OrderItem $orderItem, OrderItemDiscountsCache $itemDiscountsCache, int $discountAlreadyAppliedAmount, ?int $discountRemainingEligibleItemQuantity): array
    {
        if($discount->getAppliesOn() === DiscountAppliesOnEnum::ORDER) {
            throw new Exception("Impossible de calculer le montant de la réduction. Veuillez contacter l'administrateur");
        }

        // if(!$orderItem->getTotalAmountTtc()) return 0;

        $originalAmount = $orderItem->getBasePriceTtcPerUnit();

        $discountsCachePerUnit = $itemDiscountsCache->getCachePerUnit();

        $maxDiscountAmount = $discount->getMaxDiscountAmount();

        if($maxDiscountAmount && $discountAlreadyAppliedAmount) {
            $maxDiscountAmount -= $discountAlreadyAppliedAmount;
        }

        $originalUnitDiscountAmount = $this->calculateDiscountRawAmount($discount, $originalAmount);

        $totalDiscountAmount = 0;

        for($i = 0; $i < $orderItem->getQuantity(); $i++) {
            $discountAmount = $originalUnitDiscountAmount;

            if(isset($discountsCachePerUnit[$i])) {
                $discountAmount = $this->calculateDiscountRawAmount($discount, $discountsCachePerUnit[$i]->getDiscountCalculationBasis());
            }

            if($discountRemainingEligibleItemQuantity === 0) {
                $discountAmount = 0;
            }

            if($maxDiscountAmount && $discountAmount > $maxDiscountAmount) {
                $discountAmount = $maxDiscountAmount;
            }

            if(
                isset($discountsCachePerUnit[$i]) &&
                $discountAmount > $originalAmount - $discountsCachePerUnit[$i]->getDiscountedAmount()
            ) {
                $discountAmount = $originalAmount - $discountsCachePerUnit[$i]->getDiscountedAmount();
            }

            if($discountAmount) {
                if(!isset($discountsCachePerUnit[$i])) {
                    $discountsCachePerUnit[$i] = new OrderItemUnitDiscountsCache($orderItem->getBasePriceTtcPerUnit(), 0);
                }

                if(!$discount->getPercentageDiscount()) {
                    $discountsCachePerUnit[$i]->setDiscountCalculationBasis($discountAmount);
                }

                // DEBUG ------------
                if($discountAmount < 0) {
                    print_r($discount);
                    print_r($orderItem);
                }
                // DEBUG ------------

                $discountsCachePerUnit[$i]->setDiscountedAmount($discountAmount);

                if($discountRemainingEligibleItemQuantity) {
                    $discountRemainingEligibleItemQuantity--;
                }

                $discountAlreadyAppliedAmount += $discountAmount;

                if($maxDiscountAmount) {
                    $maxDiscountAmount -= $discountAmount;
                }

                $totalDiscountAmount += $discountAmount;
            }
        }

        if($totalDiscountAmount > 0) {
            $itemDiscountsCache->setCachePerUnit($discountsCachePerUnit);
        } elseif($totalDiscountAmount < 0) {
            $totalDiscountAmount = 0;
            // throw new Exception("Un problème est survenu lors du calcul du montant de la réduction. Veuillez contacter l'administrateur");
        }

        return [
            "discountAmount" => $totalDiscountAmount,
            "itemDiscountsCache" => $itemDiscountsCache,
            "discountAlreadyAppliedAmount" => $discountAlreadyAppliedAmount,
            "discountRemainingEligibleItemQuantity" => $discountRemainingEligibleItemQuantity
        ];
    }

    /** @return array{
     *     discountsUsedForPricing: Discount[],
     *     discountedPrice: int
     * }
     */
    public function getProductDiscountInfo(Product $product, ?User $user = null): array {
        $productDiscountInfo = [
            "discountsUsedForPricing" => [],
            "discountedPrice" => $product->getPriceTtc()
        ];

        if(!$product->getPriceTtc()) {
            return $productDiscountInfo;
        }

        $eligibleDiscounts = $this->getFilteredEligibleDiscountsForProductPricing($product, $user);

        if(empty($eligibleDiscounts)) {
            return $productDiscountInfo;
        }

        $calculationBasis = $product->getPriceTtc();
        foreach($eligibleDiscounts as $discount) {
            $result = $this->processDiscountOnProductPrice($discount, $productDiscountInfo["discountedPrice"], $calculationBasis);

            if($result["discountedPrice"] !== $productDiscountInfo["discountedPrice"]) {
                $productDiscountInfo["discountsUsedForPricing"][] = $discount;
                $productDiscountInfo["discountedPrice"] = $result["discountedPrice"];
                $calculationBasis = $result["calculationBasis"];
            }
        }

        return $productDiscountInfo;
    }

    /** @return array{
     *     discountedPrice: int,
     *     calculationBasis: int
     * }
     */
    private function processDiscountOnProductPrice(Discount $discount, int $productCurrentDiscountedPrice, int $calculationBasis): array
    {
        if($discount->getAppliesOn() !== DiscountAppliesOnEnum::ALL_ELIGIBLE_PRODUCTS) {
            throw new Exception("Impossible de calculer le montant de la réduction. Veuillez contacter l'administrateur");
        }

        $discountAmount = $this->calculateDiscountRawAmount($discount, $calculationBasis);

        if($discountAmount > $productCurrentDiscountedPrice) {
            $discountAmount = $productCurrentDiscountedPrice;
        }

        $discountedPrice = $productCurrentDiscountedPrice - $discountAmount;

        if($discountAmount && !$discount->getPercentageDiscount()) {
            $calculationBasis = $discountedPrice;
        }

        return [
            "discountedPrice" => $discountedPrice,
            "calculationBasis" => $calculationBasis
        ];
    }

    private function calculateDiscountRawAmount(Discount $discount, int $baseAmount): int
    {
        $discountAmount = round($discount->getFixedDiscount() ?? ($baseAmount * $discount->getPercentageDiscount() / 100));

        if($discount->getMaxDiscountAmount() && $discountAmount > $discount->getMaxDiscountAmount()) {
            $discountAmount = $discount->getMaxDiscountAmount();
        }

        if($discountAmount > $baseAmount) {
            $discountAmount = $baseAmount;
        }

        return $discountAmount;
    }
}