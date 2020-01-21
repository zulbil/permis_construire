<?php

namespace App\Repository;

use App\Entity\TTypeEntiteAdministrative;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TTypeEntititeAdministrative|null find($id, $lockMode = null, $lockVersion = null)
 * @method TTypeEntititeAdministrative|null findOneBy(array $criteria, array $orderBy = null)
 * @method TTypeEntititeAdministrative[]    findAll()
 * @method TTypeEntititeAdministrative[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TTypeEntiteAdministrativeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTypeEntiteAdministrative::class);
    }

    // /**
    //  * @return TTypeEntititeAdministrative[] Returns an array of TTypeEntititeAdministrative objects
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
    public function findOneBySomeField($value): ?TTypeEntititeAdministrative
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
