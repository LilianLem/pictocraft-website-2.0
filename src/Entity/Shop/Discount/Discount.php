<?php

namespace App\Entity\Shop\Discount;

use App\Entity\Core\User\User;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\WalletTransaction;
use App\Repository\Shop\Discount\DiscountRepository;
use Carbon\Carbon;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiscountRepository::class)]
#[ORM\Table(name: 'shop_discount')]
#[UniqueEntity("code", message: "Ce code est déjà utilisé")]
#[Assert\When(
    expression: "this.getStartAt() && this.getEndAt()",
    constraints: [
        new Assert\Expression(
            "this.getEndAt() > this.getStartAt()",
            message: "La date de fin doit être ultérieure à la date de début"
        )
    ]
)]
class Discount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(type: "discount_applies_on_enum")]
    #[Assert\NotBlank]
    private ?DiscountAppliesOnEnum $appliesOn = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(max: 32, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    private ?string $label = null;

    #[ORM\Column(length: 128, nullable: true)]
    #[Assert\Length(max: 128, maxMessage: "La description ne doit pas dépasser {{ limit }} caractères")]
    private ?string $privateDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 500, maxMessage: "Le texte ne doit pas dépasser {{ limit }} caractères")]
    private ?string $conditions = null;

    #[ORM\Column(length: 16, nullable: true, unique: true)]
    #[Assert\Length(min: 3, max: 16, minMessage: "Le code doit faire au minimum {{ limit }} caractères", maxMessage: "Le code ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\Regex('/^[A-Z\d]+$/', message: "Le code ne peut être composé que de majuscules non accentuées et de chiffres")]
    private ?string $code = null;

    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "La réduction fixe doit être supérieure à 0€. S'il s'agit d'un pourcentage de réduction, laissez ce champ vide")]
    #[Assert\When(
        expression: "this.getPercentageDiscount()",
        constraints: [
            new Assert\Blank(message: "Impossible d'appliquer une réduction fixe si un pourcentage de réduction est déjà défini. Laissez vide l'un des deux champs")
        ]
    )]
    private ?int $fixedDiscount = null;

    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le pourcentage de réduction doit être supérieur à 0%. S'il s'agit d'une réduction fixe, laissez ce champ vide")]
    #[Assert\LessThanOrEqual(100, message: "Le pourcentage de réduction ne peut pas dépasser 100%")]
    #[Assert\When(
        expression: "this.getFixedDiscount()",
        constraints: [
            new Assert\Blank(message: "Impossible d'appliquer un pourcentage de réduction si une réduction fixe est déjà définie. Laissez vide l'un des deux champs")
        ]
    )]
    private ?int $percentageDiscount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeInterface $endAt = null;

    // Order where appliesOn = ORDER are always applied after others regardless of priority value
    #[ORM\Column(options: ["default" => 0])]
    #[Assert\NotBlank]
    private ?int $priority = null;

    #[ORM\Column(nullable: true, options: ["unsigned" => true])]
    #[Assert\Positive(message: "La réduction maximale doit être supérieure à 0€. S'il n'y a pas de limite, laissez ce champ vide")]
    private ?int $maxDiscountAmount = null;

    #[ORM\Column(nullable: true, options: ["unsigned" => true])]
    #[Assert\Positive(message: "La quantité maximale d'articles éligibles dans le panier ne peut pas être négative. S'il n'y a pas de limite, laissez ce champ vide")]
    private ?int $maxEligibleItemQuantityInCart = null;

    #[ORM\Column(nullable: true, options: ["unsigned" => true])]
    #[Assert\Positive(message: "Le nombre d'utilisations maximal par utilisateur ne peut pas être négatif. S'il n'y a pas de limite, laissez ce champ vide")]
    private ?int $maxUsesPerUser = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(-1, message: "La quantité ne peut pas être inférieure à -1 (-1 = infini, 0 = plus utilisable)")]
    #[Assert\NotBlank]
    private ?int $quantity = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $applyAutomatically = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $enabled = null;

    #[ORM\OneToMany(mappedBy: 'discount', targetEntity: ConstraintGroup::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $constraintGroups;

    #[ORM\OneToMany(mappedBy: 'discount', targetEntity: AppliedDiscount::class)]
    private Collection $appliedDiscounts;

    #[ORM\OneToOne(mappedBy: 'generatedDiscount', cascade: ['persist', 'remove'])]
    private ?WalletTransaction $walletTransaction = null;

    #[ORM\OneToMany(mappedBy: 'discount', targetEntity: DiscountUserHistory::class, orphanRemoval: true)]
    private Collection $usersHistory;

    #[ORM\OneToMany(mappedBy: 'discount1', targetEntity: ForbiddenCombination::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $forbiddenCombinations;

    #[ORM\OneToMany(mappedBy: 'discount2', targetEntity: ForbiddenCombination::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $forbiddenCombinations2;

    public function __construct()
    {
        $this->priority = 0;
        $this->applyAutomatically = false;
        $this->enabled = false;
        $this->constraintGroups = new ArrayCollection();
        $this->appliedDiscounts = new ArrayCollection();
        $this->usersHistory = new ArrayCollection();
        $this->forbiddenCombinations = new ArrayCollection();
        $this->forbiddenCombinations2 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppliesOn(): ?DiscountAppliesOnEnum
    {
        return $this->appliesOn;
    }

    public function setAppliesOn(DiscountAppliesOnEnum $appliesOn): self
    {
        $this->appliesOn = $appliesOn;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPrivateDescription(): ?string
    {
        return $this->privateDescription;
    }

    public function setPrivateDescription(?string $privateDescription): self
    {
        $this->privateDescription = $privateDescription;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getFixedDiscount(): ?int
    {
        return $this->fixedDiscount;
    }

    public function setFixedDiscount(?int $fixedDiscount): self
    {
        if(is_int($fixedDiscount) && $this->getPercentageDiscount()) {
            throw new Exception("Impossible de définir une réduction fixe si un pourcentage de réduction est déjà appliqué.");
        }

        $this->fixedDiscount = $fixedDiscount;

        return $this;
    }

    public function getPercentageDiscount(): ?int
    {
        return $this->percentageDiscount;
    }

    public function setPercentageDiscount(?int $percentageDiscount): self
    {
        if(is_int($percentageDiscount) && $this->getFixedDiscount()) {
            throw new Exception("Impossible de définir un pourcentage de réduction si une réduction fixe est déjà appliquée.");
        }

        $this->percentageDiscount = $percentageDiscount;

        return $this;
    }

    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getMaxDiscountAmount(): ?int
    {
        return $this->maxDiscountAmount;
    }

    public function setMaxDiscountAmount(?int $maxDiscountAmount): self
    {
        $this->maxDiscountAmount = $maxDiscountAmount;

        return $this;
    }

    public function getMaxEligibleItemQuantityInCart(): ?int
    {
        return $this->maxEligibleItemQuantityInCart;
    }

    public function setMaxEligibleItemQuantityInCart(?int $maxEligibleItemQuantityInCart): self
    {
        $this->maxEligibleItemQuantityInCart = $maxEligibleItemQuantityInCart;

        return $this;
    }

    public function getMaxUsesPerUser(): ?int
    {
        return $this->maxUsesPerUser;
    }

    public function setMaxUsesPerUser(?int $maxUsesPerUser): self
    {
        $this->maxUsesPerUser = $maxUsesPerUser;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isApplyAutomatically(): ?bool
    {
        return $this->applyAutomatically;
    }

    public function setApplyAutomatically(bool $applyAutomatically): self
    {
        $this->applyAutomatically = $applyAutomatically;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection<int, ConstraintGroup>
     */
    public function getConstraintGroups(): Collection
    {
        return $this->constraintGroups;
    }

    public function addConstraintGroup(ConstraintGroup $constraintGroup): self
    {
        if (!$this->constraintGroups->contains($constraintGroup)) {
            $this->constraintGroups->add($constraintGroup);
            $constraintGroup->setDiscount($this);
        }

        return $this;
    }

    public function removeConstraintGroup(ConstraintGroup $constraintGroup): self
    {
        if ($this->constraintGroups->removeElement($constraintGroup)) {
            // set the owning side to null (unless already changed)
            if ($constraintGroup->getDiscount() === $this) {
                $constraintGroup->setDiscount(null);
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

    public function getWalletTransaction(): ?WalletTransaction
    {
        return $this->walletTransaction;
    }

    public function setWalletTransaction(?WalletTransaction $walletTransaction): self
    {
        // unset the owning side of the relation if necessary
        if ($walletTransaction === null && $this->walletTransaction !== null) {
            $this->walletTransaction->setGeneratedDiscount(null);
        }

        // set the owning side of the relation if necessary
        if ($walletTransaction !== null && $walletTransaction->getGeneratedDiscount() !== $this) {
            $walletTransaction->setGeneratedDiscount($this);
        }

        $this->walletTransaction = $walletTransaction;

        return $this;
    }

    public function isAvailable(?DateTimeInterface $date = null): bool
    {
        if(!$this->isEnabled() || !$this->getQuantity()) return false;

        $dateToCheck = new Carbon($date);
        if(
            (!is_null($this->getStartAt()) && $dateToCheck->isBefore($this->getStartAt()))
            ||
            (!is_null($this->getEndAt()) && $dateToCheck->isAfter($this->getEndAt()))
        ) {
            return false;
        }

        return true;
    }

    public function isOrderEligible(Order $order, ?DateTimeInterface $date = null): bool
    {
        if(!$this->isAvailable($date)) return false;

        if(
            $this->getMaxUsesPerUser()
            && (!$order->getUser() || $this->hasMaxUsesBeenReached($order->getUser()))
        ) {
            return false;
        }

        if($this->getConstraintGroups()->isEmpty()) return true;

        foreach($this->getConstraintGroups() as $constraintGroup) {
            if(!$constraintGroup->isOrderCompliant($order)) return false;
        }

        return true;
    }

    public function hasMaxUsesBeenReached(User $user): bool
    {
        if(!$this->getMaxUsesPerUser()) return true;

        return $this->getUserHistory($user)?->getNumberOfUses() === $this->getMaxUsesPerUser();
    }

    /**
     * @return ReadableCollection<int, OrderItem>
     */
    public function getEligibleItemsInOrder(Order $order, ?DateTimeInterface $date = null): ReadableCollection
    {
        if(!$this->isAvailable($date)) return new ArrayCollection();

        if($order->getItems()->isEmpty()) throw new Exception("Impossible de vérifier l'éligibilité de la commande à une réduction, car il n'y a aucun article dans la commande");

        if($this->getConstraintGroups()->isEmpty()) return $order->getItems();

        $eligibleItems = $order->getItems();

        foreach($this->getConstraintGroups() as $constraintGroup) {
            $groupCompliantItems = $constraintGroup->getCompliantItems($order);
            if($groupCompliantItems->isEmpty()) return new ArrayCollection();

            $eligibleItems = $eligibleItems->filter(fn(OrderItem $item) => $groupCompliantItems->findFirst(
                fn(int $key, OrderItem $compliantItem) => $item->getId() === $compliantItem->getId()
            ));

            if($eligibleItems->isEmpty()) return $eligibleItems;
        }

        return $eligibleItems;
    }

    /**
     * @return Collection<int, DiscountUserHistory>
     */
    public function getUsersHistory(): Collection
    {
        return $this->usersHistory;
    }

    public function getUserHistory(User $user): ?DiscountUserHistory
    {
        $usersHistory = $this->getUsersHistory();

        if($usersHistory->isEmpty()) return null;

        return $usersHistory->findFirst(fn(int $key, DiscountUserHistory $history) => $history->getUser() === $user);
    }

    public function addUserHistory(DiscountUserHistory $userHistory): self
    {
        if (!$this->usersHistory->contains($userHistory)) {
            $this->usersHistory->add($userHistory);
            $userHistory->setDiscount($this);
        }

        return $this;
    }

    public function removeUserHistory(DiscountUserHistory $userHistory): self
    {
        if ($this->usersHistory->removeElement($userHistory)) {
            // set the owning side to null (unless already changed)
            if ($userHistory->getDiscount() === $this) {
                $userHistory->setDiscount(null);
            }
        }

        return $this;
    }

    /**
     * @return array<int, Discount>
     */
    public function getIncompatibleDiscounts(): array
    {
        $discounts = [];

        if($this->getForbiddenCombinations()->isEmpty()) {
            return [];
        }

        foreach($this->getForbiddenCombinations() as $combination) {
            $incompatibleDiscount = $combination->getDiscount1()->getId() === $this->getId() ? $combination->getDiscount2() : $combination->getDiscount1();
            $discounts[$incompatibleDiscount->getId()] = $incompatibleDiscount;
        }

        return $discounts;
    }

    /**
     * @return Collection<int, ForbiddenCombination>
     */
    public function getForbiddenCombinations(): Collection
    {
        $forbiddenCombinations = new ArrayCollection();

        if(!$this->forbiddenCombinations->isEmpty()) {
            foreach($this->forbiddenCombinations as $combination) {
                $forbiddenCombinations->add($combination);
            }
        }

        if(!$this->forbiddenCombinations2->isEmpty()) {
            foreach($this->forbiddenCombinations2 as $combination) {
                $forbiddenCombinations->add($combination);
            }
        }

        return $forbiddenCombinations;
    }

    public function addForbiddenCombination(ForbiddenCombination $forbiddenCombination): self
    {
        if(!$forbiddenCombination->getDiscount1() && !$forbiddenCombination->getDiscount2()) {
            throw new Exception("Impossible d'ajouter une combinaison de réductions si les deux ne sont pas définies");
        }

        if(!$forbiddenCombination->getDiscount1()) {
            $forbiddenCombination->setDiscount1($this);
        } elseif(!$forbiddenCombination->getDiscount2()) {
            $forbiddenCombination->setDiscount2($this);
        }

        if (!$this->forbiddenCombinations->contains($forbiddenCombination)) {
            $this->forbiddenCombinations->add($forbiddenCombination);
        }

        return $this;
    }

    public function removeForbiddenCombination(ForbiddenCombination $forbiddenCombination): self
    {
        if ($this->forbiddenCombinations->removeElement($forbiddenCombination)) {
            $forbiddenCombination->setDiscount1(null);
            $forbiddenCombination->setDiscount2(null);
        }

        return $this;
    }
}
