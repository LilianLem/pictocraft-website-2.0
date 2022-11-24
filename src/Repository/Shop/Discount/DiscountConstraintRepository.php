<?php

namespace App\Repository\Shop\Discount;

use App\Entity\Shop\Discount\DiscountConstraint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscountConstraint>
 *
 * @method DiscountConstraint|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountConstraint|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountConstraint[]    findAll()
 * @method DiscountConstraint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountConstraintRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountConstraint::class);
    }

    public function save(DiscountConstraint $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DiscountConstraint $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DiscountConstraint[] Returns an array of DiscountConstraint objects
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

//    public function findOneBySomeField($value): ?DiscountConstraint
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
