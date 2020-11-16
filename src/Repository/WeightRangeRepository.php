<?php

namespace App\Repository;

use App\Entity\WeightRange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WeightRange|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeightRange|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeightRange[]    findAll()
 * @method WeightRange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeightRangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeightRange::class);
    }

    // /**
    //  * @return WeightRange[] Returns an array of WeightRange objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WeightRange
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
