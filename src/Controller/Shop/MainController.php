<?php

namespace App\Controller\Shop;

use App\Entity\Shop\Category;
use App\Entity\Shop\Product;
use App\Entity\Shop\ProductCategory;
use App\Repository\Shop\CategoryRepository;
use App\Repository\Shop\ProductRepository;
use App\Shop\Discount\DiscountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected DiscountService $discountService;

    public function __construct(CategoryRepository $categoryRepository, ProductRepository $productRepository, DiscountService $discountService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->discountService = $discountService;
    }

    #[Route('/shop', name: 'shop_home')]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findBy(["parent" => null, "hidden" => false, "enabled" => true]);

        return $this->render('shop/index.html.twig', ["categories" => $categories]);
    }

    #[Route('/shop/{slug1}/{slug2?}/{slug3?}/{slug4?}', name: 'shop_details', priority: -1)]
    public function details(string $slug1, ?string $slug2, ?string $slug3, ?string $slug4): Response
    {
        $slugParams = [$slug1, $slug2, $slug3, $slug4];

        $slugs = [];
        foreach($slugParams as $slug) {
            $slug ? array_unshift($slugs, $slug) : array_push($slugs, $slug);
        }

        if(!$slugs[3]) {
            $category = $this->categoryRepository->findOneBySlugs($slugs[0], $slugs[1], $slugs[2]);

            if($category) {
                $products = $category->getCategoryProducts()->map(fn(ProductCategory $cProduct) => $cProduct->getProduct())->filter(fn(Product $product) => !$product->isHidden() && $product->isEnabled() && $product->getMainCategory()->isEnabled());
                $subcategories = $category->getSubcategories()->filter(fn(Category $subcategory) => !$subcategory->isHidden() && $subcategory->isEnabled());

                return $this->render('shop/category/index.html.twig', [
                    "category" => $category,
                    "products" => $products,
                    "subcategories" => $subcategories
                ]);
            }

            if(!$slugs[1]) {
                throw $this->createNotFoundException("La catégorie est introuvable !");
            }
        }

        $product = $this->productRepository->findOneBySlugs($slugs[0], $slugs[1], $slugs[2], $slugs[3]);

        if(!$product) {
            throw $this->createNotFoundException("Le produit est introuvable !");
        }

        return $this->render('shop/product/details.html.twig', [
            "product" => $product,
            "preCartFinalPriceInfo" => $this->discountService->getProductDiscountInfo($product, $this->getUser())
        ]);
    }
}
