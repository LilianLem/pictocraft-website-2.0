<?php

namespace App\Entity\Shop\Attribute;

use App\Entity\Shop\Product;
use App\Repository\Shop\Attribute\ValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ValueRepository::class)]
#[ORM\Table(name: 'shop_attribute_value')]
#[ORM\UniqueConstraint("attribute_value_unique", columns: ["attribute_id", "value"])]
#[ORM\UniqueConstraint("attribute_value_slug_unique", columns: ["attribute_id", "slug"])]
#[UniqueEntity(
    fields: ["attribute", "value"],
    errorPath: "value",
    message: "Cette valeur existe déjà pour cet attribut",
)]
#[UniqueEntity(
    fields: ["attribute", "slug"],
    errorPath: "slug",
    message: "Ce slug est déjà utilisé pour une valeur de cet attribut",
)]
class Value
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attributeValues')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Attribute $attribute = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "La valeur ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $value = null;

    #[ORM\Column(length: 64)]
    #[Assert\Length(max: 64, maxMessage: "Le slug ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $slug = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $hidden = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'attributes')]
    private Collection $products;

    private static string $slugProperty = "value";

    public function __construct()
    {
        $this->hidden = false;
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(?Attribute $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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

    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addAttribute($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeAttribute($this);
        }

        return $this;
    }

    public static function getSlugProperty(): string
    {
        return Value::$slugProperty;
    }
}
