<?php

namespace App\Shop;

use App\Entity\Shop\Product;
use App\Repository\Shop\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService extends ShopService
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $em;
    private CategoryService $categoryService;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $em, CategoryService $categoryService)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
        $this->categoryService = $categoryService;
    }

    /**
     * @return array<string, string>
     */
    public function getRouteSlugs(Product $product): array {
        $slugs = [
            ...$this->categoryService->getRawRouteSlugs($product->getMainCategory()),
            $product->getSlug()
        ];

        return $this->addKeysToSlugArray($slugs);
    }
}