<?php

namespace App\Repository\Shop;

use App\Entity\Shop\RedemptionCodeAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RedemptionCodeAccess>
 *
 * @method RedemptionCodeAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method RedemptionCodeAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method RedemptionCodeAccess[]    findAll()
 * @method RedemptionCodeAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedemptionCodeAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedemptionCodeAccess::class);
    }

    public function save(RedemptionCodeAccess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RedemptionCodeAccess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RedemptionCodeAccess[] Returns an array of RedemptionCodeAccess objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RedemptionCodeAccess
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
