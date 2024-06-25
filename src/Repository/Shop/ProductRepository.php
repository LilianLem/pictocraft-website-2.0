<?php

namespace App\Repository\Shop;

use App\Entity\Shop\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBySlugs(string $slug, string $categorySlug, ?string $parentCategorySlug = null, ?string $parentParentCategorySlug = null): ?Product
    {
        if($parentParentCategorySlug && !$parentCategorySlug) {
            throw new Exception("Impossible de rechercher un produit correspondant : la première catégorie parente n'est pas renseignée alors que la deuxième l'est.");
        }

        $parameters = [
            "enabled" => true,
            "slug" => $slug,
            "isMain" => true,
            "categorySlug" => $categorySlug
        ];

        $query = $this->createQueryBuilder("p")
            ->leftJoin("p.productCategories", "pcat")
            ->leftJoin("pcat.category", "c")
            ->where("p.enabled = :enabled")
            ->andWhere("p.slug = :slug")
            ->andWhere("pcat.main = :isMain")
            ->andWhere("c.slug = :categorySlug")
            ->andWhere("c.enabled = :enabled")
        ;

        if($parentCategorySlug) {
            $query->leftJoin("c.parent", "pc")
                ->andWhere("pc.slug = :parentCategorySlug");

            $parameters["parentCategorySlug"] = $parentCategorySlug;

            if($parentParentCategorySlug) {
                $query->leftJoin("pc.parent", "ppc")
                    ->andWhere("ppc.slug = :parentParentCategorySlug");

                $parameters["parentParentCategorySlug"] = $parentParentCategorySlug;
            } else {
                $query->andWhere("pc.parent IS NULL");
            }
        } else {
            $query->andWhere("c.parent IS NULL");
        }

        return $query->setParameters($parameters)->getQuery()->getOneOrNullResult();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
