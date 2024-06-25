<?php

namespace App\Entity\Shop;

use App\Entity\External\Vat\VatRate;
use App\Entity\Shop\Attribute\Value;
use App\Entity\Shop\Delivery\Delivery;
use App\Entity\Shop\OrderItem\OrderItem;
use App\Repository\Shop\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
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

    #[ORM\Column(length: 64, unique: true)]
    #[Assert\Length(min: 3, max: 64, minMessage: "Le nom doit faire au minimum {{ limit }} caractères", maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    // TODO: empêcher le slug d'être identique à une catégorie
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
    private ?int $quantity = null;

    #[ORM\Column(options: ["default" => 0, "unsigned" => true])]
    #[Assert\PositiveOrZero(message: "Le prix TTC ne peut pas être négatif")]
    #[Assert\NotBlank]
    private ?int $priceTtc = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Delivery $delivery = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductCategory::class, orphanRemoval: true)]
    private Collection $productCategories;

    #[ORM\ManyToMany(targetEntity: Value::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: "shop_product_attribute_value")]
    private Collection $attributes;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderItem::class, orphanRemoval: true, cascade: ["persist", "remove"])]
    private Collection $orderItems;

    #[ORM\OneToOne(mappedBy: 'product', cascade: ['persist', 'remove'])]
    private ?ProductAutomaticDelivery $automaticDeliveryData = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?VatRate $vatRate = null;

    public function __construct()
    {
        $this->buyable = false;
        $this->hidden = false;
        $this->enabled = false;
        $this->quantity = 0;
        $this->priceTtc = 0;
        $this->productCategories = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPriceHt(): ?int
    {
        if(is_null($this->getApplicableVatRate())) {
            throw new Exception("Impossible de calculer le prix HT car aucun taux de TVA n'est relié au produit ou à sa catégorie.");
        }

        return $this->getApplicableVatRate()->getCurrentValue()->getHtPriceFromTtc($this->priceTtc);
    }

//    public function setPriceHt(int $priceHt): self
//    {
//        $this->priceHt = $priceHt;
//
//        return $this;
//    }

    public function getPriceTtc(): ?int
    {
        return $this->priceTtc;
    }

    public function setPriceTtc(int $priceTtc): self
    {
        $this->priceTtc = $priceTtc;

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
     * @param bool $onlyEnabled Does not apply to main category
     * @return Collection<int, ProductCategory>
     */
    public function getProductCategories(bool $onlySecondary = false, bool $onlyEnabled = true): Collection
    {
        if(!$onlySecondary && !$onlyEnabled) {
            return $this->productCategories;
        }

        $condition = $onlySecondary && $onlyEnabled
            ? fn(ProductCategory $pCategory): bool => !$pCategory->isMain() && $pCategory->getCategory()->isEnabled()
            : ($onlyEnabled
                ? fn(ProductCategory $pCategory): bool => $pCategory->isMain() || $pCategory->getCategory()->isEnabled()
                : fn(ProductCategory $pCategory): bool => !$pCategory->isMain()
            );

        return $this->productCategories->filter(fn(ProductCategory $pCategory): bool => $condition($pCategory));
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

    public function getMainCategory(): ?Category
    {
        if($this->productCategories->isEmpty()) return null;

        $productCategory = $this->productCategories->findFirst(fn(int $key, ProductCategory $pc): bool => $pc->isMain());
        return $productCategory?->getCategory() ?? null;
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
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    // TODO: remove method
    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setProduct($this);
        }

        return $this;
    }

    // TODO: remove method
    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
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

    public function getVatRate(): ?VatRate
    {
        return $this->vatRate;
    }

    public function setVatRate(?VatRate $vatRate): self
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    public function getInheritedVatRate(): ?VatRate
    {
        return $this->getMainCategory()?->getApplicableVatRate() ?? null;
    }

    public function getApplicableVatRate(): ?VatRate
    {
        return $this->getVatRate() ?? $this->getInheritedVatRate();
    }
}
