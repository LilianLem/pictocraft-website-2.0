<?php

namespace App\Repository\Shop;

use App\Entity\Shop\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBySlugs(string $slug, ?string $parentSlug = null, ?string $parentParentSlug = null): ?Category
    {
        if($parentParentSlug && !$parentSlug) {
            throw new Exception("Impossible de rechercher une catégorie correspondante : la première catégorie parente n'est pas renseignée alors que la deuxième l'est.");
        }

        // TODO: cette fonction ne marche pas : voir si le problème est lié au fait que certains slugs de parents soient null

        $parameters = [
            "enabled" => true,
            "slug" => $slug
        ];

        $query = $this->createQueryBuilder("c")
            ->where("c.enabled = :enabled")
            ->andWhere("c.slug = :slug")
        ;

        if($parentSlug) {
            $query->leftJoin("c.parent", "pc")
                ->andWhere("pc.slug = :parentSlug");

            $parameters["parentSlug"] = $parentSlug;

            if($parentParentSlug) {
                $query->leftJoin("pc.parent", "ppc")
                    ->andWhere("ppc.slug = :parentParentSlug");

                $parameters["parentParentSlug"] = $parentParentSlug;
            } else {
                $query->andWhere("pc.parent IS NULL");
            }
        } else {
            $query->andWhere("c.parent IS NULL");
        }

        return $query->setParameters($parameters)->getQuery()->getOneOrNullResult();
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
