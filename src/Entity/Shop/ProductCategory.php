<?php

namespace App\Entity\Shop;

use App\Repository\Shop\ProductCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
#[ORM\Table(name: 'shop_product_category')]
#[ORM\UniqueConstraint("product_category_unique", columns: ["product_id", "category_id"])]
#[UniqueEntity(
    fields: ["product", "category"],
    errorPath: "category",
    message: "Ce produit possède déjà cette catégorie",
)]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'productCategories', fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Product $product = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'categoryProducts', fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Category $category = null;

    #[ORM\Column(options: ["default" => false])]
    #[Assert\NotBlank]
    private ?bool $main = null;

    public function __construct()
    {
        $this->main = false;
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

    public function isMain(): ?bool
    {
        return $this->main;
    }

    public function setMain(bool $main): self
    {
        $this->main = $main;

        return $this;
    }
}
