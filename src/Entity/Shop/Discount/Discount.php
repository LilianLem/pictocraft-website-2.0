<?php

namespace App\Entity\Shop\Discount;

use App\Repository\Shop\Discount\DiscountRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

// TODO: prevent adding a discount applying on an order with fixedDiscount set (VAT problem)
// TODO: prevent adding a discount with fixedDiscount & percentageDiscount set at the same time
#[ORM\Entity(repositoryClass: DiscountRepository::class)]
#[ORM\Table(name: 'shop_discount')]
#[UniqueEntity("code", message: "Ce code est déjà utilisé")]
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
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "La réduction fixe doit être supérieure à 0€. S'il s'agit d'un pourcentage de réduction, laissez ce champ vide")]
    private ?int $fixedDiscount = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "Le pourcentage de réduction doit être supérieur à 0%. S'il s'agit d'une réduction fixe, laissez ce champ vide")]
    #[Assert\LessThanOrEqual(100, message: "Le pourcentage de réduction ne peut pas dépasser 100%")]
    private ?int $percentageDiscount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\DateTime]
    private ?DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTimeInterface $endAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $priority = null;

    #[ORM\Column(nullable: true, options: ["unsigned" => true])]
    #[Assert\Positive(message: "La réduction maximale doit être supérieure à 0€. S'il n'y a pas de limite, laissez ce champ vide")]
    private ?int $maxDiscountAmount = null;

    #[ORM\Column(nullable: true, options: ["unsigned" => true])]
    #[Assert\PositiveOrZero(message: "La quantité maximale d'articles éligibles dans le panier ne peut pas être négative. S'il n'y a pas de limite, laissez ce champ vide")]
    private ?int $maxEligibleItemQuantityInCart = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(-1, message: "La quantité ne peut pas être inférieure à -1 (-1 = infini, 0 = plus utilisable)")]
    #[Assert\NotBlank]
    private ?int $quantity = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $enabled = null;

    #[ORM\OneToMany(mappedBy: 'discount', targetEntity: ConstraintGroup::class, orphanRemoval: true)]
    private Collection $constraintGroups;

    #[ORM\OneToMany(mappedBy: 'discount', targetEntity: AppliedDiscount::class)]
    private Collection $appliedDiscounts;

    public function __construct()
    {
        $this->enabled = false;
        $this->constraintGroups = new ArrayCollection();
        $this->appliedDiscounts = new ArrayCollection();
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
        $this->fixedDiscount = $fixedDiscount;

        return $this;
    }

    public function getPercentageDiscount(): ?int
    {
        return $this->percentageDiscount;
    }

    public function setPercentageDiscount(?int $percentageDiscount): self
    {
        $this->percentageDiscount = $percentageDiscount;

        return $this;
    }

    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeInterface $startAt): self
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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
}
