<?php

namespace App\Repository\Shop\RedemptionCode;

use App\Entity\Shop\RedemptionCode\RedemptionCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RedemptionCode>
 *
 * @method RedemptionCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method RedemptionCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method RedemptionCode[]    findAll()
 * @method RedemptionCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedemptionCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedemptionCode::class);
    }

    public function save(RedemptionCode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RedemptionCode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RedemptionCode[] Returns an array of RedemptionCode objects
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

//    public function findOneBySomeField($value): ?RedemptionCode
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
