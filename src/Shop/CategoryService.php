<?php

namespace App\Shop;

use App\Entity\Shop\Category;
use App\Repository\Shop\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService extends ShopService
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $em;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }


    /**
     * @return array<string, string>
     */
    public function getRouteSlugs(Category $category): array {
        return $this->addKeysToSlugArray(
            $this->getRawRouteSlugs($category)
        );
    }

    /**
     * @return string[]
     */
    public function getRawRouteSlugs(Category $category): array {
        $slugs = [$category->getSlug()];

        $parentSlug = $category->getParent()?->getSlug();
        $parentParentSlug = $category->getParent()?->getParent()?->getSlug();

        if($parentSlug) {
            array_unshift($slugs, $parentSlug);

            if($parentParentSlug) {
                array_unshift($slugs, $parentParentSlug);
            }
        }

        return $slugs;
    }
}