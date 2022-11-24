<?php

namespace App\Repository\Shop\Discount;

use App\Entity\Shop\Discount\AppliedDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppliedDiscount>
 *
 * @method AppliedDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppliedDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppliedDiscount[]    findAll()
 * @method AppliedDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppliedDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppliedDiscount::class);
    }

    public function save(AppliedDiscount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AppliedDiscount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AppliedDiscount[] Returns an array of AppliedDiscount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AppliedDiscount
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
