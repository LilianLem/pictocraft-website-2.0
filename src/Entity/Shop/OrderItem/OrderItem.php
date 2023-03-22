<?php

namespace App\Entity\Shop\OrderItem;

use App\Entity\Core\User\User;
use App\Entity\Shop\Delivery\Delivery;
use App\Entity\Shop\Discount\AppliedDiscount;
use App\Entity\Shop\GameKey\GameKey;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Product;
use App\Repository\Shop\OrderItem\OrderItemRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ORM\Table(name: 'shop_order_item')]
#[ORM\UniqueConstraint("order_item_unique", columns: ["order_id", "product_id"])]
#[UniqueEntity(
    fields: ["order", "product"],
    errorPath: "product",
    message: "Cet article est déjà présent dans la commande",
)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Order $order = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Product $product = null;

    #[ORM\Column(name: "base_price_ttc_per_unit", options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix de base TTC unitaire ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $basePriceTTCPerUnit = null;

    // Final price (multiplied by quantity, with applied item discounts)
    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix TTC total ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $totalPriceTTC = null;

    #[Assert\PositiveOrZero]
    private ?int $discountsTotal = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Le commentaire ne doit pas dépasser {{ limit }} caractères")]
    private ?string $comment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Delivery $delivery = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    #[Assert\DateTime]
    private ?DateTimeImmutable $createdAt = null;

    // TODO : vérifier si la mise à jour de la date est fonctionnelle, càd seulement quand des choses sont modifiées
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "CURRENT_TIMESTAMP"], columnDefinition: "DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL on update CURRENT_TIMESTAMP")]
    #[Assert\DateTime]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\Column(options: ["default" => 1, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "La quantité ne peut pas être négative")]
    #[Assert\NotBlank]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'giftedItems')]
    private ?User $giftedTo = null;

    #[ORM\OneToMany(mappedBy: 'orderItem', targetEntity: GameKey::class)]
    private Collection $gameKeys;

    #[ORM\OneToMany(mappedBy: 'orderItem', targetEntity: AppliedDiscount::class)]
    private Collection $appliedDiscounts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryTrackingLink = null;

    #[ORM\OneToMany(mappedBy: 'orderItem', targetEntity: Status::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $statusHistory;

    // Indique l'article qui a servi à renouveler cet article
    #[ORM\OneToOne(inversedBy: 'precededBy', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $followedBy = null;

    // Indique l'article d'origine qui a été renouvelé par cet article
    #[ORM\OneToOne(mappedBy: 'followedBy', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $precededBy = null;

    public function __construct()
    {
        $this->basePriceTTCPerUnit = 0;
        $this->totalPriceTTC = 0;
        $this->quantity = 1;
        $this->gameKeys = new ArrayCollection();
        $this->appliedDiscounts = new ArrayCollection();
        $this->statusHistory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        if(!$this->getBasePriceTTCPerUnit()) {
            $this->setBasePriceTTCPerUnit($product->getPriceTTC());
        }

        if($this->getQuantity()) {
            $this->updateTotalPriceTTC();
        }

        return $this;
    }

    public function getBasePriceHTPerUnit(): ?int
    {
        if(is_null($this->getBasePriceTTCPerUnit())) {
            throw new Exception("Le prix de base TTC unitaire doit d'abord être défini avant de calculer le prix de base HT unitaire");
        }

        if(is_null($this->getProduct())) {
            throw new Exception("Impossible de calculer le prix HT unitaire car aucun produit n'est défini.");
        }

        if(is_null($this->getProduct()->getApplicableVatRate())) {
            throw new Exception("Impossible de calculer le prix HT unitaire car aucun taux de TVA n'est relié au produit ou à sa catégorie.");
        }

        return $this->getProduct()->getApplicableVatRate()->getValueAtDate($this->getCreatedAt() ?? new DateTime())->getHTPriceFromTTC($this->getBasePriceTTCPerUnit());
    }

    public function getBasePriceTTCPerUnit(): ?int
    {
        return $this->basePriceTTCPerUnit;
    }

    public function setBasePriceTTCPerUnit(int $basePriceTTCPerUnit): self
    {
        $this->basePriceTTCPerUnit = $basePriceTTCPerUnit;

        if($this->getProduct() && $this->getQuantity()) {
            $this->updateTotalPriceTTC();
        }

        return $this;
    }

    public function getTotalPriceHT(): ?int
    {
        if(is_null($this->getBasePriceTTCPerUnit())) {
            throw new Exception("Le prix de base TTC unitaire doit d'abord être défini avant d'obtenir le prix HT total");
        }

        if(is_null($this->getQuantity())) {
            throw new Exception("La quantité doit d'abord être définie avant de mettre à jour le prix HT total");
        }

        if(is_null($this->getProduct()->getApplicableVatRate())) {
            throw new Exception("Impossible de calculer le prix HT total car aucun taux de TVA n'est relié au produit ou à sa catégorie.");
        }

        $totalPriceHT = $this->getBasePriceHTPerUnit() * $this->getQuantity() - $this->getProduct()->getApplicableVatRate()->getValueAtDate($this->getCreatedAt() ?? new DateTime())->getHTPriceFromTTC($this->getDiscountsTotal());

        // Not supposed to happen
        if($totalPriceHT < 0) $totalPriceHT = 0;

        return $totalPriceHT;
    }

    public function getTotalPriceTTC(): ?int
    {
        return $this->totalPriceTTC;
    }

//    public function setTotalPriceTTC(int $totalPriceTTC): self
//    {
//        $this->totalPriceTTC = $totalPriceTTC;
//
//        return $this;
//    }

    // Order update needed separately after prices update
    public function updateTotalPriceTTC(): self
    {
        if(is_null($this->getBasePriceTTCPerUnit())) {
            throw new Exception("Le prix de base TTC unitaire doit d'abord être défini avant de mettre à jour le prix TTC total");
        }

        if(is_null($this->getQuantity())) {
            throw new Exception("La quantité doit d'abord être définie avant de mettre à jour le prix TTC total");
        }

        $this->totalPriceTTC = $this->getBasePriceTTCPerUnit() * $this->getQuantity() - $this->getDiscountsTotal();

        // Not supposed to happen
        if($this->totalPriceTTC < 0) $this->totalPriceTTC = 0;

        return $this;
    }

    public function getDiscountsTotal(): ?int
    {
        $this->discountsTotal = $this->getAppliedDiscounts()->isEmpty()
            ? 0
            : $this->getAppliedDiscounts()->reduce(fn(int $accumulator, AppliedDiscount $ad): int => $accumulator + $ad->getAmount(), initial: 0);

        return $this->discountsTotal;
    }

//    public function setDiscountsTotal(int $discountsTotal): self
//    {
//        $this->discountsTotal = $discountsTotal;
//
//        return $this;
//    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        if($this->getProduct()) {
            $this->updateTotalPriceTTC();
        }

        // TODO: update discounts amounts and eligibilities accordingly

        return $this;
    }

    public function getGiftedTo(): ?User
    {
        return $this->giftedTo;
    }

    public function setGiftedTo(?User $giftedTo): self
    {
        $this->giftedTo = $giftedTo;

        return $this;
    }

    /**
     * @return Collection<int, GameKey>
     */
    public function getGameKeys(): Collection
    {
        return $this->gameKeys;
    }

    public function addGameKey(GameKey $gameKey): self
    {
        if (!$this->gameKeys->contains($gameKey)) {
            $this->gameKeys->add($gameKey);
            $gameKey->setOrderItem($this);
        }

        return $this;
    }

    public function removeGameKey(GameKey $gameKey): self
    {
        if ($this->gameKeys->removeElement($gameKey)) {
            // set the owning side to null (unless already changed)
            if ($gameKey->getOrderItem() === $this) {
                $gameKey->setOrderItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AppliedDiscount>
     */
    public function getAppliedDiscounts(): Collection
    {
        return $this->appliedDiscounts;
    }

    public function addAppliedDiscount(AppliedDiscount $appliedDiscount): self
    {
        if(is_null($this->getBasePriceTTCPerUnit())) {
            throw new Exception("Le prix de base TTC doit d'abord être défini avant de pouvoir appliquer des réductions");
        }

        if(is_null($this->getQuantity())) {
            throw new Exception("La quantité doit d'abord être définie avant de pouvoir appliquer des réductions");
        }

        if (!$this->appliedDiscounts->contains($appliedDiscount)) {
            $this->appliedDiscounts->add($appliedDiscount);
            $appliedDiscount->setOrderItem($this);
        }

        return $this;
    }

    public function removeAppliedDiscount(AppliedDiscount $appliedDiscount): self
    {
        if ($this->appliedDiscounts->removeElement($appliedDiscount)) {
            // set the owning side to null (unless already changed)
            if ($appliedDiscount->getOrderItem() === $this) {
                $appliedDiscount->setOrderItem(null);
            }
        }

        return $this;
    }

    public function getDeliveryTrackingLink(): ?string
    {
        return $this->deliveryTrackingLink;
    }

    public function setDeliveryTrackingLink(?string $deliveryTrackingLink): self
    {
        $this->deliveryTrackingLink = $deliveryTrackingLink;

        return $this;
    }

    /**
     * @return Collection<int, Status>
     */
    public function getStatusHistory(): Collection
    {
        return $this->statusHistory;
    }

    // TODO : méthode à retravailler pour juste donner le statut à ajouter
    public function addStatusToHistory(Status $statusHistory): self
    {
        if (!$this->statusHistory->contains($statusHistory)) {
            $this->statusHistory->add($statusHistory);
            $statusHistory->setOrderItem($this);
        }

        return $this;
    }

    public function removeStatusFromHistory(Status $statusHistory): self
    {
        if ($this->statusHistory->removeElement($statusHistory)) {
            // set the owning side to null (unless already changed)
            if ($statusHistory->getOrderItem() === $this) {
                $statusHistory->setOrderItem(null);
            }
        }

        return $this;
    }

    public function getCurrentStatus(): ?Status
    {
        $statusHistory = $this->getStatusHistory();
        return $statusHistory?->last() ?? null;
    }

    public function getStatusDetails(StatusEnum $status): ?Status
    {
        $statusHistory = $this->getStatusHistory();
        return $statusHistory?->findFirst(fn(int $key, Status $statusToCompare) => $statusToCompare->getStatus() === $status) ?? null;
    }

    public function getFollowedBy(): ?self
    {
        return $this->followedBy;
    }

    public function setFollowedBy(?self $followedBy): self
    {
        $this->followedBy = $followedBy;

        return $this;
    }

    public function getPrecededBy(): ?self
    {
        return $this->precededBy;
    }

    public function setPrecededBy(?self $precededBy): self
    {
        // unset the owning side of the relation if necessary
        if ($precededBy === null && $this->precededBy !== null) {
            $this->precededBy->setFollowedBy(null);
        }

        // set the owning side of the relation if necessary
        if ($precededBy !== null && $precededBy->getFollowedBy() !== $this) {
            $precededBy->setFollowedBy($this);
        }

        $this->precededBy = $precededBy;

        return $this;
    }
}
