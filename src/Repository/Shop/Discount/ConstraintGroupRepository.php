<?php

namespace App\Repository\Shop\Discount;

use App\Entity\Shop\Discount\ConstraintGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConstraintGroup>
 *
 * @method ConstraintGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConstraintGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConstraintGroup[]    findAll()
 * @method ConstraintGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConstraintGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConstraintGroup::class);
    }

    public function save(ConstraintGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConstraintGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ConstraintGroup[] Returns an array of ConstraintGroup objects
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

//    public function findOneBySomeField($value): ?ConstraintGroup
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
