<?php

namespace App\Repository;

use App\Entity\Personne;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Personne
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Utilisateur $user
     * @param array $parameters
     * @return mixed
     * Cette fonction execute une requete permettant de faire un filtre
     */
    public function filterPersonnebyUser (Utilisateur $user, $parameters = array()) {
        $qb =  $this->createQueryBuilder('p')
                    ->where('p.utilisateur = :user')
                    ->setParameter('user', $user);
        if(isset($parameters["query"]["generalSearch"]) && !empty($parameters["query"]["generalSearch"])) {
            $search = $parameters["query"]["generalSearch"];
            $qb->andWhere( $qb->expr()->orX(
                $qb->expr()->like('p.nom', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.postnom', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.prenom', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.email', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.activite', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.adresse', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.forme_juridique', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.numero_registre_commerce', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.numero_id_nationale', $qb->expr()->literal("%$search%")),
                $qb->expr()->like('p.nationalite', $qb->expr()->literal("%$search%"))
            ));
        }

        if (isset($parameters["query"]["type"]) && !empty($parameters["query"]["type"])){
            $qb->andWhere('p.forme_juridique = :type')
                ->setParameter('type', $parameters['query']['type']);
        }
        if (isset($parameters["sort"]["field"]) && !empty($parameters["sort"]["field"])) {
            $sort = strtoupper($parameters["sort"]["sort"]);

            if ($parameters["sort"]["field"] == "id") {
                $qb->orderBy('p.id', $sort);
            }
            if ($parameters["sort"]["field"] == "nom") {
                $qb->orderBy('p.nom', $sort);
            }
            if ($parameters["sort"]["field"] == "postnom") {
                $qb->orderBy('p.postnom', $sort);
            }
            if($parameters["sort"]["field"] == "prenom") {
                $qb->orderBy('p.prenom', $sort);
            }
            if($parameters["sort"]["field"] == "email") {
                $qb->orderBy('p.email', $sort);
            }
            if($parameters["sort"]["field"] == "email") {
                $qb->orderBy('p.adresse', $sort);
            }
            if($parameters["sort"]["field"] == "telephone") {
                $qb->orderBy('p.telephone', $sort);
            }
        }

        $query = $qb->getQuery();

        return $query->execute();
    }
}
