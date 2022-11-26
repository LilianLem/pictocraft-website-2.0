<?php

namespace App\Entity\Shop\Discount;

use App\Entity\Core\Role;
use App\Entity\Core\User\User;
use App\Entity\Shop\AttributeValue;
use App\Entity\Shop\Category;
use App\Entity\Shop\Product;
use App\Repository\Shop\Discount\DiscountConstraintRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiscountConstraintRepository::class)]
#[ORM\Table(name: 'shop_discount_constraint')]
#[ORM\UniqueConstraint("discount_constraint_unique", columns: ["constraint_group_id", "product_id", "category_id", "attribute_value_id", "user_id", "role_id"])]
#[UniqueEntity(
    fields: ["constraintGroup", "product", "category", "attributeValue", "user", "role"],
    errorPath: "constraintGroup",
    ignoreNull: true,
    message: "L'un des éléments renseignés est déjà pris en compte dans ce groupe de contraintes",
)]
class DiscountConstraint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'constraints')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?DiscountConstraintGroup $constraintGroup = null;

    #[ORM\ManyToOne]
    private ?Product $product = null;

    #[ORM\ManyToOne]
    private ?Category $category = null;

    #[ORM\ManyToOne]
    private ?AttributeValue $attributeValue = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?Role $role = null;

    // TODO : contrainte à ajouter pour que la valeur de cette propriété soit inférieure à celle de maxProductPrice
    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le prix minimum du produit doit être supérieur à 0€. S'il n'y en a pas, laissez ce champ vide")]
    private ?int $minProductPrice = null;

    // TODO : contrainte à ajouter pour que la valeur de cette propriété soit supérieure à celle de minProductPrice
    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le prix maximum du produit doit être supérieur à 0€. S'il n'y en a pas, laissez ce champ vide")]
    private ?int $maxProductPrice = null;

    // TODO : contrainte à ajouter pour que la valeur de cette propriété soit inférieure à celle de maxOrderAmount
    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le montant minimum de la commande doit être supérieur à 0€. S'il n'y en a pas, laissez ce champ vide")]
    private ?int $minOrderAmount = null;

    // TODO : contrainte à ajouter pour que la valeur de cette propriété soit supérieure à celle de minOrderAmount
    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le montant maximum de la commande doit être supérieur à 0€. S'il n'y en a pas, laissez ce champ vide")]
    private ?int $maxOrderAmount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConstraintGroup(): ?DiscountConstraintGroup
    {
        return $this->constraintGroup;
    }

    public function setConstraintGroup(?DiscountConstraintGroup $constraintGroup): self
    {
        $this->constraintGroup = $constraintGroup;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAttributeValue(): ?AttributeValue
    {
        return $this->attributeValue;
    }

    public function setAttributeValue(?AttributeValue $attributeValue): self
    {
        $this->attributeValue = $attributeValue;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getMinProductPrice(): ?int
    {
        return $this->minProductPrice;
    }

    public function setMinProductPrice(?int $minProductPrice): self
    {
        $this->minProductPrice = $minProductPrice;

        return $this;
    }

    public function getMaxProductPrice(): ?int
    {
        return $this->maxProductPrice;
    }

    public function setMaxProductPrice(?int $maxProductPrice): self
    {
        $this->maxProductPrice = $maxProductPrice;

        return $this;
    }

    public function getMinOrderAmount(): ?int
    {
        return $this->minOrderAmount;
    }

    public function setMinOrderAmount(?int $minOrderAmount): self
    {
        $this->minOrderAmount = $minOrderAmount;

        return $this;
    }

    public function getMaxOrderAmount(): ?int
    {
        return $this->maxOrderAmount;
    }

    public function setMaxOrderAmount(?int $maxOrderAmount): self
    {
        $this->maxOrderAmount = $maxOrderAmount;

        return $this;
    }
}
