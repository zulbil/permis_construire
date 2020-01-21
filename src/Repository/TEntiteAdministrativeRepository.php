<?php

namespace App\Repository;

use App\Entity\TEntiteAdministrative;
use App\Entity\TTerritorial; 
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TEntiteAdministrative|null find($id, $lockMode = null, $lockVersion = null)
 * @method TEntiteAdministrative|null findOneBy(array $criteria, array $orderBy = null)
 * @method TEntiteAdministrative[]    findAll()
 * @method TEntiteAdministrative[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TEntiteAdministrativeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TEntiteAdministrative::class);
    }

    // /**
    //  * @return TEntiteAdministrative[] Returns an array of TEntiteAdministrative objects
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
    public function findOneBySomeField($value): ?TEntiteAdministrative
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
     * @param $search
     * @param $id_territorial
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findAllRelatedEntitesAdmin($search, $id_territorial) : array {

        $conn = $this->getEntityManager()->getConnection(); 

        /*$sql = '
                SELECT *
                FROM t_entite_administrative e
                WHERE e.fkTypeentite IN
                (
                    SELECT t.Fk_TypeEntite
                    FROM t_territorial t
                    WHERE t.Fk_TypeTerritorial = :id_territorial
                )
                AND e.IntituleEntite LIKE :search
            '; */
        $sql = '
                SELECT *
                FROM T_Entite_Administrative e
                WHERE e.Fk_TypeEntite IN
                (
                    SELECT tt.Fk_TypeEntite
                    FROM T_Territorial tt
                    INNER JOIN T_Type_Entite_Administrative  ON  
                    tt.Fk_TypeEntite = T_Type_Entite_Administrative.IdTypeEntite
                    WHERE tt.Fk_TypeTerritorial = :id_territorial
                )
                AND e.IntituleEntite LIKE :search
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id_territorial' => $id_territorial, 'search' => "%$search%"]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /**
     * @param $id_entitemere
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getChildEntitesAdmin ($id_entitemere, $type_territorial, $tea) : array {
        $conn = $this->getEntityManager()->getConnection(); 

        $sql    = '
            SELECT ea.IdEntite, ea.IntituleEntite, ea.Fk_TypeEntite, ea.Fk_EntiteMere 
            FROM T_Entite_Administrative ea 
            LEFT JOIN T_Territorial tt ON ea.Fk_TypeEntite=tt.Fk_TypeEntite 
            WHERE tt.Fk_TypeTerritorial= :type_territorial 
            AND ea.Fk_EntiteMere IN 
            (   SELECT IdEntite 
                FROM T_Entite_Administrative 
                WHERE IdEntite = :id_entite 
                AND Fk_TypeEntite= :type_entite
            )
        
        ';

        $stmt = $conn->prepare($sql);
        
        $stmt->execute([
            'id_entite'         => $id_entitemere, 
            'type_territorial'  => $type_territorial,
            'type_entite'       => $tea
        ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /**
     * @param $id_entitemere
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getEntitesMereAdmin ($id_ena, $id_tea, $id_type_territorial) : array {
        $conn = $this->getEntityManager()->getConnection(); 

        $sql = '
            SELECT * FROM T_Entite_Administrative 
            WHERE IdEntite 
            IN ( 
                SELECT Fk_EntiteMere FROM T_Entite_Administrative ea  
                INNER JOIN T_Territorial tt ON tt.Fk_TypeEntite = ea.Fk_TypeEntite 
                WHERE ea.IdEntite = :id_ena AND 
                ea.Fk_TypeEntite = :id_tea AND 
                tt.Fk_TypeTerritorial = :id_type_territorial 
            )
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['id_ena' => $id_ena, 'id_tea' => $id_tea, 'id_type_territorial' => $id_type_territorial ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function addEntites($intitule, $denomination, $entite_mere, $tea) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            CALL P_NEW_ENTITE_ADMINISTRATIVE(:intitule, :denomination, :entite_mere, :tea)
        ';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'intitule'      => $intitule,
            'denomination'  => $denomination,
            'entite_mere'   => $entite_mere,
            'tea'           => $tea
        ]);


        $sqlSelect = '
                        SELECT ea.IdEntite 
                        FROM T_Entite_Administrative ea 
                        WHERE ea.IntituleEntite = :intitule
                        AND  ea.DenominationHabitant = :denomination
                        AND ea.Fk_EntiteMere = :entite_mere
                        AND ea.Fk_TypeEntite = :tea
                      ';
        $statement = $conn->prepare($sqlSelect);

        $statement->execute([
            'intitule'      => $intitule,
            'denomination'  => $denomination,
            'entite_mere'   => $entite_mere,
            'tea'           => $tea
        ]);

        return $statement->fetch();

    }
}
