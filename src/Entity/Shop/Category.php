<?php

namespace App\Entity\Shop;

use App\Repository\Shop\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'shop_category')]
#[ORM\UniqueConstraint("category_unique", columns: ["name", "parent_id"])]
#[ORM\UniqueConstraint("category_slug_unique", columns: ["slug", "parent_id"])]
#[UniqueEntity(
    fields: ["name", "parent"],
    errorPath: "name",
    message: "Cette catégorie existe déjà. Si tu souhaites donner un même nom à des sous-catégories de catégories différentes, il faut d'abord créer la catégorie principale, puis la sélectionner lors de la création de la sous-catégorie",
)]
#[UniqueEntity(
    fields: ["slug", "parent"],
    errorPath: "slug",
    message: "Ce slug est déjà utilisé sur une catégorie avec le même parent",
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    #[Assert\Length(min: 3, max: 32, minMessage: "Le nom doit faire au minimum {{ limit }} caractères", maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 32)]
    #[Assert\Length(max: 32, maxMessage: "Le slug ne doit pas dépasser {{ limit }} caractères")]
    #[Assert\NotBlank]
    private ?string $slug = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $hidden = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $enabled = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'getSubcategories')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $subcategories;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: ProductCategory::class, orphanRemoval: true)]
    private Collection $categoryProducts;

    public function __construct()
    {
        $this->hidden = false;
        $this->enabled = false;
        $this->subcategories = new ArrayCollection();
        $this->categoryProducts = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(self $subcategory): self
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories->add($subcategory);
            $subcategory->setParent($this);
        }

        return $this;
    }

    public function removeSubcategory(self $subcategory): self
    {
        if ($this->subcategories->removeElement($subcategory)) {
            // set the owning side to null (unless already changed)
            if ($subcategory->getParent() === $this) {
                $subcategory->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductCategory>
     */
    public function getCategoryProducts(): Collection
    {
        return $this->categoryProducts;
    }

    public function addCategoryProduct(ProductCategory $categoryProduct): self
    {
        if (!$this->categoryProducts->contains($categoryProduct)) {
            $this->categoryProducts->add($categoryProduct);
            $categoryProduct->setCategory($this);
        }

        return $this;
    }

    public function removeCategoryProduct(ProductCategory $categoryProduct): self
    {
        if ($this->categoryProducts->removeElement($categoryProduct)) {
            // set the owning side to null (unless already changed)
            if ($categoryProduct->getCategory() === $this) {
                $categoryProduct->setCategory(null);
            }
        }

        return $this;
    }
}
