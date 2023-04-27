<?php

namespace App\Repository\Shop\Discount;

use App\Entity\Shop\Discount\DiscountUserHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscountUserHistory>
 *
 * @method DiscountUserHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountUserHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountUserHistory[]    findAll()
 * @method DiscountUserHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountUserHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountUserHistory::class);
    }

    public function save(DiscountUserHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DiscountUserHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DiscountUserHistory[] Returns an array of DiscountUserHistory objects
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

//    public function findOneBySomeField($value): ?DiscountUserHistory
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
