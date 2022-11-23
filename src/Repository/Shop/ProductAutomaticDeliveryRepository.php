<?php

namespace App\Repository\Shop;

use App\Entity\Shop\ProductAutomaticDelivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductAutomaticDelivery>
 *
 * @method ProductAutomaticDelivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAutomaticDelivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAutomaticDelivery[]    findAll()
 * @method ProductAutomaticDelivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAutomaticDeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAutomaticDelivery::class);
    }

    public function save(ProductAutomaticDelivery $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductAutomaticDelivery $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ProductAutomaticDelivery[] Returns an array of ProductAutomaticDelivery objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductAutomaticDelivery
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
