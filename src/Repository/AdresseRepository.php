<?php

namespace App\Repository;

use App\Entity\Personne;
use App\Entity\Adresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Adresse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adresse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adresse[]    findAll()
 * @method Adresse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adresse::class);
    }

    // /**
    //  * @return Adresse[] Returns an array of Adresse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Adresse
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param Personne $personne
     * @return mixed
     * Cette fonction permet de récuperer une adresse principale selon la personne fournie
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getMainAdresse(Personne $personne) {

        $conn = $this->getEntityManager()->getConnection();

        $sql    =   '
            SELECT *
            FROM adresse a
            INNER JOIN adresse_personne ap  ON  
            a.id = ap.adresse_id
            WHERE ap.personne_id = :personne_id
            AND  a.par_defaut = 1
        ';

        $stmt = $conn->prepare($sql);

        $stmt->execute(['personne_id' => $personne->getId() ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetch();

    }
}
