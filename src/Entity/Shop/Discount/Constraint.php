<?php

namespace App\Entity\Shop\Discount;

use App\Entity\Core\Role\Role;
use App\Entity\Core\Role\RoleUser;
use App\Entity\Core\User\User;
use App\Entity\Shop\Attribute\Value;
use App\Entity\Shop\Category;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Entity\Shop\Product;
use App\Entity\Shop\ProductCategory;
use App\Repository\Shop\Discount\ConstraintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConstraintRepository::class)]
#[ORM\Table(name: 'shop_discount_constraint')]
#[ORM\UniqueConstraint("discount_constraint_unique", columns: ["constraint_group_id", "product_id", "category_id", "attribute_value_id", "user_id", "role_id"])]
#[UniqueEntity(
    fields: ["constraintGroup", "product", "category", "attributeValue", "user", "role"],
    errorPath: "constraintGroup",
    ignoreNull: true,
    message: "L'un des éléments renseignés est déjà pris en compte dans ce groupe de contraintes",
)]
#[Assert\When(
    expression: "this.getMinProductPrice() && this.getMaxProductPrice()",
    constraints: [
        new Assert\Expression(
            "this.getMaxProductPrice() > this.getMinProductPrice()",
            message: "Le prix maximum du produit doit être supérieur au prix minimum"
        )
    ]
)]
#[Assert\When(
    expression: "this.getMinOrderAmount() && this.getMaxOrderAmount()",
    constraints: [
        new Assert\Expression(
            "this.getMaxOrderAmount() > this.getMinOrderAmount()",
            message: "Le montant maximum de la commande doit être supérieur au montant minimum"
        )
    ]
)]
#[Assert\When(
    expression: "this.getMinQuantity() && this.getMaxQuantity()",
    constraints: [
        new Assert\Expression(
            "this.getMaxQuantity() > this.getMinQuantity()",
            message: "La quantité maximale doit être supérieur à la quantité minimale"
        )
    ]
)]
class Constraint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'constraints')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?ConstraintGroup $constraintGroup = null;

    #[ORM\ManyToOne]
    private ?Product $product = null;

    #[ORM\ManyToOne]
    private ?Category $category = null;

    #[ORM\ManyToOne]
    private ?Value $attributeValue = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?Role $role = null;

    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le prix minimum du produit doit être supérieur à 0€. S'il n'y en a pas, laissez ce champ vide")]
    private ?int $minProductPrice = null;

    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le prix maximum du produit doit être supérieur à 0€. S'il n'y en a pas, laissez ce champ vide")]
    private ?int $maxProductPrice = null;

    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le montant minimum de la commande doit être supérieur à 0€. S'il n'y en a pas, laissez ce champ vide")]
    private ?int $minOrderAmount = null;

    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "Le montant maximum de la commande doit être supérieur à 0€. S'il n'y en a pas, laissez ce champ vide")]
    private ?int $maxOrderAmount = null;

    // La quantité s'entend par le total des articles correspondant à la contrainte (ex. : s'il y a une contrainte de catégorie, tous les articles de cette catégorie sont pris en compte)
    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "La quantité minimale doit être supérieure à 0")]
    private ?int $minQuantity = null;

    #[ORM\Column(options: ["unsigned" => true], nullable: true)]
    #[Assert\Positive(message: "La quantité maximale doit être supérieure à 0. S'il n'y a pas de limite, laissez ce champ vide")]
    private ?int $maxQuantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConstraintGroup(): ?ConstraintGroup
    {
        return $this->constraintGroup;
    }

    public function setConstraintGroup(?ConstraintGroup $constraintGroup): self
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

    public function getAttributeValue(): ?Value
    {
        return $this->attributeValue;
    }

    public function setAttributeValue(?Value $attributeValue): self
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

    public function getMinQuantity(): ?int
    {
        return $this->minQuantity;
    }

    public function setMinQuantity(?int $minQuantity): self
    {
        $this->minQuantity = $minQuantity;

        return $this;
    }

    public function getMaxQuantity(): ?int
    {
        return $this->maxQuantity;
    }

    public function setMaxQuantity(?int $maxQuantity): self
    {
        $this->maxQuantity = $maxQuantity;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>|bool
     */
    public function getOrderCompliance(Order $order, bool $returnCompliantItems = false): Collection|bool
    {
        $matchingItems = $this->checkOrderRules($order) ? $this->filterOutOrderItemsByItemRules($order) : new ArrayCollection();

        return $returnCompliantItems ? $matchingItems : !$matchingItems->isEmpty();
    }

    private function checkOrderRules(Order $order): bool
    {
        if($this->getMinOrderAmount() && $order->getTotalAmountTtc() < $this->getMinOrderAmount()) return false;

        if($this->getMaxOrderAmount() && $order->getTotalAmountTtc() > $this->getMaxOrderAmount()) return false;

        if(($this->getUser() || $this->getRole()) && !$order->getUser()) return false;

        if($this->getUser() && $order->getUser()->getId() !== $this->getUser()->getId()) return false;

        if($this->getRole() && !$order->getUser()->getFullRoles()->exists(fn(int $key, RoleUser $roleUser) => $roleUser->getRole()->getId() === $this->getRole()->getId())) return false;

        return true;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    private function filterOutOrderItemsByItemRules(Order $order): Collection
    {
        if($order->getItems()->isEmpty()) throw new Exception("Impossible de vérifier l'éligibilité de la commande à une réduction, car il n'y a aucun article dans la commande");

        $matchingItems = $order->getItems();

        if($this->getProduct()) $matchingItems = $matchingItems->filter(fn(OrderItem $item) => $item->getProduct()->getId() === $this->getProduct()->getId());

        if($this->getCategory()) $matchingItems = $matchingItems->filter(fn(OrderItem $item) => $item->getProduct()->getProductCategories()->exists(fn(int $key, ProductCategory $pCategory) => $pCategory->getCategory()->getId() === $this->getCategory()->getId()));

        if($this->getAttributeValue()) $matchingItems = $matchingItems->filter(fn(OrderItem $item) => $item->getProduct()->getAttributes()->exists(fn(int $key, Value $attrValue) => $attrValue->getId() === $this->getAttributeValue()->getId()));

        if($this->getMinProductPrice()) $matchingItems = $matchingItems->filter(fn(OrderItem $item) => $item->getProduct()->getPriceTtc() >= $this->getMinProductPrice());

        if($this->getMaxProductPrice()) $matchingItems = $matchingItems->filter(fn(OrderItem $item) => $item->getProduct()->getPriceTtc() <= $this->getMaxProductPrice());

        if($matchingItems->isEmpty()) return new ArrayCollection();

        if($this->getMinQuantity() || $this->getMaxQuantity()) {
            $totalMatchingItemsQuantity = $matchingItems->reduce(fn(int $sum, OrderItem $item) => $sum + $item->getQuantity());

            if(
                ($this->getMinQuantity() && $totalMatchingItemsQuantity < $this->getMinQuantity())
                || ($this->getMaxQuantity() && $totalMatchingItemsQuantity > $this->getMaxQuantity())
            ) {
                return new ArrayCollection();
            }
        }

        return $matchingItems;
    }

    public function isProductCompliant(Product $product, ?User $user = null): bool
    {
        if($this->getProduct() && $product->getId() !== $this->getProduct()->getId()) return false;

        if($this->getCategory() && !$product->getProductCategories()->exists(fn(int $key, ProductCategory $pCategory) => $pCategory->getCategory()->getId() === $this->getCategory()->getId())) return false;

        if($this->getAttributeValue() && !$product->getAttributes()->exists(fn(int $key, Value $attrValue) => $attrValue->getId() === $this->getAttributeValue()->getId())) return false;

        if($this->getMinProductPrice() && $product->getPriceTtc() < $this->getMinProductPrice()) return false;

        if($this->getMaxProductPrice() && $product->getPriceTtc() > $this->getMaxProductPrice()) return false;

        if(($this->getUser() || $this->getRole()) && !$user) return false;

        if($this->getUser() && $user->getId() !== $this->getUser()->getId()) return false;

        if($this->getRole() && !$user->getFullRoles()->exists(fn(int $key, RoleUser $roleUser) => $roleUser->getRole()->getId() === $this->getRole()->getId())) return false;

        return true;
    }
}
