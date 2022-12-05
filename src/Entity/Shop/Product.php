<?php

namespace App\Entity\Shop;

use App\Entity\Shop\Attribute\Value;
use App\Entity\Shop\Delivery\Delivery;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Repository\Shop\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'shop_product')]
#[UniqueEntity("name", message: "Ce produit existe déjà")]
#[UniqueEntity("slug", message: "Ce slug est déjà utilisé")]
#[UniqueEntity("reference", message: "Cette référence est déjà utilisée")]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(min: 3, max: 64, minMessage: "Le nom doit faire au minimum {{ limit }} caractères", maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 64, unique: true)]
    #[Assert\Length(max: 64, maxMessage: "Le slug ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $slug = null;

    #[ORM\Column(length: 4)]
    #[Assert\Length(exactly: 4, exactMessage: "La référence doit compter exactement {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $reference = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 2000, maxMessage: "La description ne doit pas dépasser {{ limit }} caractères")]
    private ?string $description = null;

    #[ORM\Column(length: 48, nullable: true)]
    #[Assert\Length(min: 3, max: 48, minMessage: "Le texte doit faire au minimum {{ limit }} caractères", maxMessage: "Le texte ne doit pas dépasser {{ limit }} caractères")]
    private ?string $subtitle = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le nom du fichier ne doit pas dépasser {{ limit }} caractères")]
    private ?string $image = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $buyable = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $hidden = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $enabled = null;

    #[ORM\Column(options: ["default" => 0])]
    #[Assert\GreaterThanOrEqual(-1, message: "La quantité ne peut pas être inférieure à -1 (-1 = infini, 0 = plus de stock)")]
    #[Assert\NotBlank]
    private ?int $amount = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix de base HT ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $basePriceHT = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix de base TTC ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $basePriceTTC = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix HT ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $priceHT = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix TTC ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $priceTTC = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64, maxMessage: "Le texte ne doit pas dépasser {{ limit }} caractères")]
    private ?string $publicDiscountText = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Delivery $delivery = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductCategory::class, orphanRemoval: true)]
    private Collection $productCategories;

    #[ORM\ManyToMany(targetEntity: Value::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: "shop_product_attribute_value")]
    private Collection $attributes;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: OrderItem::class, orphanRemoval: true)]
    private Collection $orders;

    #[ORM\OneToOne(mappedBy: 'product', cascade: ['persist', 'remove'])]
    private ?ProductAutomaticDelivery $automaticDeliveryData = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->productCategories = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isBuyable(): ?bool
    {
        return $this->buyable;
    }

    public function setBuyable(bool $buyable): self
    {
        $this->buyable = $buyable;

        return $this;
    }

    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBasePriceHT(): ?int
    {
        return $this->basePriceHT;
    }

    public function setBasePriceHT(int $basePriceHT): self
    {
        $this->basePriceHT = $basePriceHT;

        return $this;
    }

    public function getBasePriceTTC(): ?int
    {
        return $this->basePriceTTC;
    }

    public function setBasePriceTTC(int $basePriceTTC): self
    {
        $this->basePriceTTC = $basePriceTTC;

        return $this;
    }

    public function getPriceHT(): ?int
    {
        return $this->priceHT;
    }

    public function setPriceHT(int $priceHT): self
    {
        $this->priceHT = $priceHT;

        return $this;
    }

    public function getPriceTTC(): ?int
    {
        return $this->priceTTC;
    }

    public function setPriceTTC(int $priceTTC): self
    {
        $this->priceTTC = $priceTTC;

        return $this;
    }

    public function getPublicDiscountText(): ?string
    {
        return $this->publicDiscountText;
    }

    public function setPublicDiscountText(?string $publicDiscountText): self
    {
        $this->publicDiscountText = $publicDiscountText;

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

    /**
     * @return Collection<int, ProductCategory>
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    public function addProductCategory(ProductCategory $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories->add($productCategory);
            $productCategory->setProduct($this);
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            // set the owning side to null (unless already changed)
            if ($productCategory->getProduct() === $this) {
                $productCategory->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Value>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Value $attribute): self
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
        }

        return $this;
    }

    public function removeAttribute(Value $attribute): self
    {
        $this->attributes->removeElement($attribute);

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(OrderItem $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setItem($this);
        }

        return $this;
    }

    public function removeOrder(OrderItem $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getItem() === $this) {
                $order->setItem(null);
            }
        }

        return $this;
    }

    public function getAutomaticDeliveryData(): ?ProductAutomaticDelivery
    {
        return $this->automaticDeliveryData;
    }

    public function setAutomaticDeliveryData(ProductAutomaticDelivery $automaticDeliveryData): self
    {
        // set the owning side of the relation if necessary
        if ($automaticDeliveryData->getProduct() !== $this) {
            $automaticDeliveryData->setProduct($this);
        }

        $this->automaticDeliveryData = $automaticDeliveryData;

        return $this;
    }
}
