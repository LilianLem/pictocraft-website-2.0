<?php

namespace App\Repository\Shop\Discount;

use App\Entity\Shop\Discount\DiscountConstraintGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscountConstraintGroup>
 *
 * @method DiscountConstraintGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountConstraintGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountConstraintGroup[]    findAll()
 * @method DiscountConstraintGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountConstraintGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountConstraintGroup::class);
    }

    public function save(DiscountConstraintGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DiscountConstraintGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DiscountConstraintGroup[] Returns an array of DiscountConstraintGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DiscountConstraintGroup
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
