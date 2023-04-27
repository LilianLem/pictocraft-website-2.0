<?php

namespace App\Repository\Shop\Discount;

use App\Entity\Shop\Discount\ForbiddenCombination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForbiddenCombination>
 *
 * @method ForbiddenCombination|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForbiddenCombination|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForbiddenCombination[]    findAll()
 * @method ForbiddenCombination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountForbiddenCombinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForbiddenCombination::class);
    }

    public function save(ForbiddenCombination $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ForbiddenCombination $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DiscountForbiddenCombination[] Returns an array of DiscountForbiddenCombination objects
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

//    public function findOneBySomeField($value): ?DiscountForbiddenCombination
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
