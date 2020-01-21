<?php

namespace App\Repository;

use App\Entity\TTypeTerritorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TTypeTerritorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method TTypeTerritorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method TTypeTerritorial[]    findAll()
 * @method TTypeTerritorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TTypeTerritorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTypeTerritorial::class);
    }

    // /**
    //  * @return TTypeTerritorial[] Returns an array of TTypeTerritorial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TTypeTerritorial
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
