<?php

namespace App\Repository\External\Geo\France;

use App\Entity\External\Geo\France\CommunePostalData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommunePostalData>
 *
 * @method CommunePostalData|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunePostalData|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunePostalData[]    findAll()
 * @method CommunePostalData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunePostalDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunePostalData::class);
    }

    public function save(CommunePostalData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommunePostalData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getMaxId(): int
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select("MAX(cpd.id)")
            ->from("App:External\Geo\France\CommunePostalData", "cpd")
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return CommunePostalData[] Returns an array of CommunePostalData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CommunePostalData
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
