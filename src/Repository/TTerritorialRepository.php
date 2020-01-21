<?php

namespace App\Repository;

use App\Entity\TTerritorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TTerritorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method TTerritorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method TTerritorial[]    findAll()
 * @method TTerritorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TTerritorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTerritorial::class);
    }

    // /**
    //  * @return TTerritorial[] Returns an array of TTerritorial objects
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
    public function findOneBySomeField($value): ?TTerritorial
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $id_territorial
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getMaxTypeTerritorial($id_territorial): ?array {
        $conn = $this->getEntityManager()->getConnection(); 

        $sql = '
                SELECT *
                FROM T_Territorial tt
                WHERE tt.OrdreTerritorial =
                (
                    SELECT max(OrdreTerritorial)
                    FROM T_Territorial t
                    WHERE t.Fk_TypeTerritorial = :id_territorial
                )
                AND tt.Fk_TypeTerritorial = :id_territorial
            '; 

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id_territorial' => $id_territorial]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /**
     * @param $typeterritorial
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAllTypesEnties($typeterritorial): ?array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT tt.OrdreTerritorial, T_Type_Entite_Administrative.IdTypeEntite, T_Type_Entite_Administrative.IntituleTypeEntite
                FROM T_Territorial tt
                INNER JOIN T_Type_Entite_Administrative  ON  
                tt.Fk_TypeEntite = T_Type_Entite_Administrative.IdTypeEntite
                WHERE tt.Fk_TypeTerritorial = :type_territorial
                ORDER BY tt.OrdreTerritorial DESC
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['type_territorial' => $typeterritorial]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function getLibelleType($type_entite){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT tea.IntituleTypeEntite 
                FROM T_Type_Entite_Administrative tea  
                WHERE  tea.IdTypeEntite = :type_entite
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['type_entite' => $type_entite]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll()[0]['IntituleTypeEntite'];
    }
}
