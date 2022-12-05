<?php

namespace App\Repository\Modules\Survey;

use App\Entity\Modules\Survey\SurveyUserAnonymous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SurveyUserAnonymous>
 *
 * @method SurveyUserAnonymous|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyUserAnonymous|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyUserAnonymous[]    findAll()
 * @method SurveyUserAnonymous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyUserAnonymousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyUserAnonymous::class);
    }

    public function save(SurveyUserAnonymous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SurveyUserAnonymous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SurveyUserAnonymous[] Returns an array of SurveyUserAnonymous objects
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

//    public function findOneBySomeField($value): ?SurveyUserAnonymous
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
