<?php

namespace App\Entity\External\Vat;

use App\Entity\Shop\Category;
use App\Entity\Shop\Product;
use App\Repository\External\Vat\VatRateRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VatRateRepository::class)]
#[ORM\Table(name: 'vat_rate')]
class VatRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 32, unique: true)]
    #[Assert\Length(max: 32, maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    //private ?Value $currentValue = null;
    #[ORM\OneToMany(mappedBy: 'rate', targetEntity: Value::class, orphanRemoval: true)]
    #[ORM\OrderBy(["endAt" => "ASC"])]
    private Collection $valueHistory;

    #[ORM\OneToMany(mappedBy: 'vatRate', targetEntity: Product::class, cascade: ["persist"])]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'defaultVatRate', targetEntity: Category::class, cascade: ["persist"])]
    private Collection $productCategories;

    public function __construct()
    {
        $this->valueHistory = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->productCategories = new ArrayCollection();
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

    public function getCurrentValue(): ?Value
    {
        if(empty($this->valueHistory)) {
            return null;
        }

        $currentValue = $this->valueHistory->findFirst(fn(int $key, Value $value) => !is_null($value->getEndAt()) && $value->getEndAt()->diff(new DateTime())->invert);

        return $currentValue ?? $this->valueHistory->findFirst(fn(int $key, Value $value) => is_null($value->getEndAt()));
    }

    public function getValueAtDate(DateTimeInterface $date): ?Value
    {
        if(empty($this->valueHistory)) {
            return null;
        }

        $value = $this->valueHistory->findFirst(fn(int $key, Value $value) => !is_null($value->getEndAt()) && $value->getEndAt()->diff($date)->invert);

        return $value ?? $this->valueHistory->findFirst(fn(int $key, Value $value) => is_null($value->getEndAt()));
    }

    /**
     * @return Collection<int, Value>
     */
    public function getValueHistory(): Collection
    {
        return $this->valueHistory;
    }

    public function addValueToRate(Value $value): self
    {
        if (!$this->valueHistory->contains($value)) {
            $this->valueHistory->add($value);
            $value->setRate($this);
        }

        return $this;
    }

    public function removeValueFromRate(Value $value): self
    {
        if ($this->valueHistory->removeElement($value)) {
            // set the owning side to null (unless already changed)
            if ($value->getRate() === $this) {
                $value->setRate(null);
            }
        }

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
            $product->setVatRate($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getVatRate() === $this) {
                $product->setVatRate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    public function addProductCategory(Category $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories->add($productCategory);
            $productCategory->setDefaultVatRate($this);
        }

        return $this;
    }

    public function removeProductCategory(Category $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            // set the owning side to null (unless already changed)
            if ($productCategory->getDefaultVatRate() === $this) {
                $productCategory->setDefaultVatRate(null);
            }
        }

        return $this;
    }
}
