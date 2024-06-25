<?php

namespace App\Repository\Shop\Discount;

use App\Entity\Shop\Discount\Discount;
use App\Entity\Shop\Discount\DiscountAppliesOnEnum;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discount>
 *
 * @method Discount|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discount|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discount[]    findAll()
 * @method Discount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discount::class);
    }

    public function save(Discount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Discount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Discount[] Returns an array of Discount objects
     */
    public function findAvailable(?bool $appliedAutomatically = null, ?DiscountAppliesOnEnum $onlyAppliesOn = null): array
    {
        $query = $this->createQueryBuilder("d")
            ->andWhere("d.enabled = :enabled")
            ->andWhere("d.startAt < :now")
            ->orWhere("d.startAt is null")
            ->andWhere("d.endAt > :now")
            ->orWhere("d.endAt is null")
            ->andWhere("d.quantity <> :quantity");

        if(!is_null($appliedAutomatically)) {
            $query->andWhere("d.applyAutomatically = :applyAutomatically")
                ->setParameter("applyAutomatically", $appliedAutomatically);
        }

        if(!is_null($onlyAppliesOn)) {
            $query->andWhere("d.appliesOn = :appliesOn")
                ->setParameter("appliesOn", $onlyAppliesOn);
        }

        $query->setParameter("enabled", true)
            ->setParameter("now", new DateTime())
            ->setParameter("quantity", 0)
            ->addOrderBy("d.priority", "DESC")
            ->addOrderBy("d.appliesOn", "DESC")
            ->addOrderBy("d.fixedDiscount", "DESC")
            ->addOrderBy("d.percentageDiscount", "DESC")
            ->addOrderBy("d.id", "ASC");

        if(is_null($onlyAppliesOn)) {
            $orderDiscountsQuery = clone $query;

            $query->andWhere("d.appliesOn <> :appliesOn")
                ->setParameter("appliesOn", DiscountAppliesOnEnum::ORDER);

            $orderDiscountsQuery->andWhere("d.appliesOn = :appliesOn")
                ->setParameter("appliesOn", DiscountAppliesOnEnum::ORDER);
        }

        /** @var array $queryResult */
        $queryResult = $query->getQuery()->getResult();

        if(is_null($onlyAppliesOn)) {
            /** @var array $orderDiscountsQueryResult */
            $orderDiscountsQueryResult = $orderDiscountsQuery->getQuery()->getResult();

            return array_merge($queryResult, $orderDiscountsQueryResult);
        }

        return($queryResult);
    }

//    /**
//     * @return Discount[] Returns an array of Discount objects
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

//    public function findOneBySomeField($value): ?Discount
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
