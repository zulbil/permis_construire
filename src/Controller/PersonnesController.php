<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Personne;
use App\Entity\TTypeterritorial;
use App\Entity\TEntiteAdministrative;
use App\Entity\TTypeEntiteAdministrative;
use App\Entity\PersonnePhysique;
use App\Entity\Utilisateur;
use App\Entity\TTerritorial;
use App\Form\PersonnePhysiqueType;
use App\Form\PersonneType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class PersonnesController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class PersonnesController extends AbstractController
{
    private $security;
    private $entityManager;
    private $user;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager        =   $entityManager;
        $this->security             =   $security;
        $this->user                 =   $this->security->getUser();
    }

    /**
     *
     */
    public function index()
    {
        return $this->render('personnes/index.html.twig', [
            'controller_name' => 'PersonnesController',
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\
     * @Route("/personnes/physiques", name="personnes_physiques")
     */
    public function getPersonnesPhysique() {
        $data                           = array();
        $personnes_physiques            = $this->user->getPersonnePhysiques();
        $data['personnes_physiques']    = $personnes_physiques;
        $data['page']                   = 'Les Personnes Physiques';

        return $this->render('personnes/personnes_physiques.html.twig', $data);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/personnes/physiques/data", name="physiques_data")
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getAllPersonnesPhysiques() {
        $encoder    =   new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer =   new Serializer([$normalizer], [$encoder]);
        $personnes_physiques            = $this->user->getPersonnePhysiques();

        $jsonContent    =   $serializer->normalize($personnes_physiques, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'utilisateur','sexe','profession', 'lieu_de_naissance', 'date_de_naissance', 'etat_civil', 'lieuDeNaissance', 'dateDeNaissance','nationalite','NIF'
            ]
        ]);

        return $this->json(["personnes" => $jsonContent ]);
    }

    /**
     * @param Request $request
     * Cette fonction permet d'ajouter une personne physique
     * @Route("/personnes/physiques/ajouter", name="ajouter_personne_physique")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addPersonnePhysique(Request $request) {
        $data               = array();
        $personne_physique  =   new PersonnePhysique();
        $form               =   $this->createForm(PersonnePhysiqueType::class, $personne_physique);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() )
        {
            $personne   = $form->getData();
            $date_naissance = new \DateTime($form->get('date_de_naissance')->getData());
            $personne->setDateDeNaissance($date_naissance);
            $personne->setUtilisateur($this->user);
            $this->entityManager->persist($personne);
            $this->entityManager->flush();

            return $this->redirectToRoute('personnes_physiques');
        }

        $data['form']   = $form->createView();
        $data['page']   =   'Ajouter une personne physique';

        return $this->render('personnes/ajouter-personne-physique.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/personnes/physiques/editer/{id}", name="editer_personne_physique")
     * @author Joel Alexandre Khang Zulbal
     * Cette fonction renvoie à une page qui permet d'editer une personne physique
     */
    public function editPersonnePhysique(Request $request, int $id) {
        $data       =   array();
        $personne_physique  =   $this->entityManager->getRepository(PersonnePhysique::class)->find($id);
        $form   =   $this->createForm(PersonnePhysiqueType::class, $personne_physique);
        $date_naissance     = $personne_physique->getDateDeNaissance();
        $data['date_naissance']     =   isset($date_naissance) ? $date_naissance->format("Y-m-d") : null;

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $personne = $form->getData();
            $personne->setUtilisateur($this->user);
            $this->entityManager->persist($personne);
            $this->entityManager->flush();

            return $this->redirectToRoute('personnes_physiques');
        }

        $data['form']   =   $form->createView();
        $data['page']   = 'Modifier une personne physique';

        return $this->render('personnes/modifier-personne-physique.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param PersonnePhysique $personne
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * Cette fonction permet de supprimer une personne physique
     * @Route("/personnes/physiques/supprimer/{id}", name="supprimer_personne")
     */
    public function removePersonnePhysique(Request $request, PersonnePhysique $personne)
    {
        $this->entityManager->remove($personne);
        $this->entityManager->flush();

        return $this->json(["deleted" => true ], [204]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/personnes/ajouter", name="ajouter_personne")
     * @throws \Exception
     */
    public function addNewPersonne(Request $request) {
        $data   =   array();

        $form   = $this->createForm(PersonneType::class, new Personne());

        $data['page']   =   'Ajouter une Personne';
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $personne   = $form->getData();
            $date_naissance = new \DateTime($form->get('date_de_naissance')->getData());
            $personne->setDateDeNaissance($date_naissance);
            //var_dump($personne); exit();
            $personne->setUtilisateur($this->user);
            $new_adresse        =   new Adresse();

            $new_adresse->setEtat(1);
            $new_adresse->setParDefaut(1);
            $new_adresse->setAdresseComplete($form->get('adresse')->getData());
            $new_adresse->setNumero($form->get('numero')->getData());
            $fk_entite = $form->get('fk_entite')->getData();
            $entite_admin = $this->entityManager->getRepository(TEntiteAdministrative::class)->find($fk_entite);
            $new_adresse->setFkEntite($entite_admin);
            $new_adresse->addPersonne($personne);

            $this->entityManager->persist($personne);
            $this->entityManager->persist($new_adresse);

            $this->entityManager->flush();

            return $this->redirectToRoute('les_personnes');
        }

        $data['form']           =   $form->createView();
        //$data['form_adress']    =   $form_adresse->createView(); 

        return $this->render('personnes/ajouter-personne.html.twig', $data );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/personnes/data", name="listes_personnes")
     */
    public function getAllPersonnes(Request $request) {
        $parameters             = $request->request->all();

        $encoder    =   new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer =   new Serializer([$normalizer], [$encoder]);

        $personnes  = $this->user->getPersonne();

        if(isset($parameters) && !empty($parameters)) {
            $personnes  = $this->entityManager->getRepository(Personne::class)->filterPersonnebyUser($this->user, $parameters);
        }


        $jsonContent    =   $serializer->normalize($personnes, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'utilisateur','sexe','profession', 'lieu_de_naissance', 'date_de_naissance', 'etat_civil', 'lieuDeNaissance', 'dateDeNaissance','nationalite','NIF', 'adresses'
            ]
        ]);

        return $this->json(["personnes" => $jsonContent ]);
    }

    /**
     * @Route("/personnes", name="les_personnes")
     */
    public function getPersonnes() {
        $data           =   array();
        $data['page']   =   'Liste des personnes';

        return $this->render('personnes/index.html.twig', $data);
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/personnes/{id}", name="une_personne")
     */
    public function getPersonneById($id) {
        $personne = $this->entityManager->getRepository(Personne::class)->find($id);

        return  $this->json(["personne"  => $personne ]);
    }

    /**
     * @param Personne $personne
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/personnes/supprimer/{id}", name="supprimer_personne")
     * @ParamConverter("personne", class="App\Entity\Personne")
     */
    public function removePersonne(Personne $personne) {

        $this->entityManager->remove($personne);
        $this->entityManager->flush();

        return $this->json(["deleted" => true ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/personnes/editer/{id}", name="personne_editer")
     */
    public function editPersonne(int $id, Request $request) {

        $personne   =   $this->entityManager->getRepository(Personne::class)->find($id);

        $adresse    =   $this->entityManager->getRepository(Adresse::class)->getMainAdresse($personne);

        $adresse_obj = null;

        $data   =   array();

        $form   = $this->createForm(PersonneType::class, $personne);

        if ($adresse) {
            $form->get('fk_entite')->setData($adresse['fk_entite']);
            $form->get('numero')->setData($adresse['numero']);
            $id = $adresse['adresse_id'];
            $adresse_obj    =  $this->entityManager->getRepository(Adresse::class)->find($id);
        }

        $data['page']   =   'Editer une Personne';

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Modification d'une personne
            $personne       = $form->getData();
            $date_naissance = new \DateTime($form->get('date_de_naissance')->getData());
            $personne->setDateDeNaissance($date_naissance);
            $personne->setUtilisateur($this->user);

            $new_adresse    =   null;
            $adresse_to_register = null;
            // Test s'il existe une adresse assignée à cette personne
            if ($adresse) {
                $id = $adresse['adresse_id'];
                $adresse_obj    =  $this->entityManager->getRepository(Adresse::class)->find($id);
                $adresse_obj->setEtat(1);
                $adresse_obj->setParDefaut(1);
                $adresse_obj->setAdresseComplete($form->get('adresse')->getData());
                $adresse_obj->setNumero($form->get('numero')->getData());
                $fk_entite = $form->get('fk_entite')->getData();
                $entite_admin = $this->entityManager->getRepository(TEntiteAdministrative::class)->find($fk_entite);
                $adresse_obj->setFkEntite($entite_admin);
                $adresse_to_register = $adresse_obj;
            } else {
                $new_adresse        =   new Adresse();
                $new_adresse->setEtat(1);
                $new_adresse->setParDefaut(1);
                $new_adresse->setAdresseComplete($form->get('adresse')->getData());
                $numero = $form->get('numero')->getData();
                if ($numero) {
                    $new_adresse->setNumero($numero);
                }
                $fk_entite = $form->get('fk_entite')->getData();
                $entite_admin = $this->entityManager->getRepository(TEntiteAdministrative::class)->find($fk_entite);
                $new_adresse->setFkEntite($entite_admin);
                $new_adresse->addPersonne($personne);
                $adresse_to_register = $new_adresse;
            }
            $this->entityManager->persist($personne);
            $this->entityManager->persist($adresse_to_register);

            $this->entityManager->flush();

            return $this->redirectToRoute('les_personnes');
        }
        $data['form']   =   $form->createView();

        return $this->render('personnes/editer-personne.html.twig', $data );
    }

    /**
     * @param Request $request
     * @Route("/personne/check/nrc/{nrc}", name="check_nrc_personne")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function checkNRCPersonne ($nrc, Request $request) {

        $encoder    =   new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];

        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer =   new Serializer([$normalizer], [$encoder]);
        $personne = $this->entityManager->getRepository(Personne::class)->findOneBy(['numero_registre_commerce' => $nrc ]);
        $jsonContent    =   $serializer->normalize($personne, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'utilisateur','sexe','fonction', 'lieu_de_naissance', 'date_de_naissance', 'etat_civil', 'lieuDeNaissance', 'dateDeNaissance','nationalite','NIF'
            ]
        ]);

        return $this->json(['personne' => $jsonContent ]);
    }

    /**
     * 
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/personne/adresse/type_territorial", name="get_types_territorial")
     */
    public function getAllTypesTerritorial() {
        $type = $this->entityManager->getRepository(TTypeterritorial::class)->findAll(); 
        $data = array('types' => $type ); 

        return $this->json($data);
    }

    /**
     * @param Request $request
     * @Route("/personne/adresse/entite_administratives", name="les_entites_admin")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAllEntitiesAdmin(Request $request) {
        $search            = $request->request->get('search');
        $id_territorial    = $request->request->get('type_territorial');

        $entities   = $this->entityManager
                            ->getRepository(TEntiteAdministrative::class)
                            ->findAllRelatedEntitesAdmin($search, $id_territorial);
        
        $entites_array      =   array();

        foreach ($entities as $entitie) {
            $object_entitie             = array();
            $object_entitie['idEntite']   = $entitie["IdEntite"];
            $object_entitie['typeEntiteA'] = $entitie["Fk_TypeEntite"];     
            $object_entitie['intitule']   = $entitie["IntituleEntite"];
            $object_entitie['typeentite'] = $this->entityManager
                                                ->getRepository(TTypeEntiteAdministrative::class)
                                                ->find($entitie['Fk_TypeEntite'])
                                                ->getIntituletypeentite(); 
            $entite_mere    =   $this->entityManager
                                        ->getRepository(TEntiteAdministrative::class)
                                        ->find($entitie['Fk_EntiteMere']);                                 

            $object_entitie['intitule_mere'] = isset($entite_mere) ? $entite_mere->getIntituleentite() : ""; 
            
            $object_entitie['typeentite_mere'] = isset($entite_mere) ? $entite_mere->getFkTypeentite()->getIntituletypeentite() : ""; 

                                                
            array_push($entites_array, $object_entitie); 
            
        }                    
        
        return $this->json(['search' => $search, 'id_territorial' => $id_territorial, 'entites' => $entites_array ]);
        
    }

    /**
     * @param Request $request
     * @Route("/personne/adresse/entite_administratives/data/{search}/{type_territorial}", name="les_entites_admin_data")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getEntities($search, $type_territorial) {
        // $search            = $request->request->get('search');
        // $id_territorial    = $request->request->get('type_territorial');

        $entities   = $this->entityManager
                            ->getRepository(TEntiteAdministrative::class)
                            ->findAllRelatedEntitesAdmin($search, $type_territorial);
         
        $entites_array      =   array();

        foreach ($entities as $entitie) {
            $object_entitie                 = array();
            $object_entitie['idEntite']     = $entitie["IdEntite"];
            $object_entitie['typeEntiteA']  = $entitie["Fk_TypeEntite"];
            $object_entitie['intitule']     = $entitie["IntituleEntite"];
            $object_entitie['typeentite']   = $this->entityManager
                                               ->getRepository(TTypeEntiteAdministrative::class)
                                               ->find($entitie['Fk_TypeEntite'])
                                               ->getIntituletypeentite(); 
            $entite_mere    =   $this->entityManager
                                     ->getRepository(TEntiteAdministrative::class)
                                     ->find($entitie['Fk_EntiteMere']);                                 

            $object_entitie['intitule_mere'] = isset($entite_mere) ? $entite_mere->getIntituleentite() : ""; 

            $object_entitie['typeentite_mere'] = $entite_mere->getFkTypeentite()->getIntituletypeentite(); 

                                              
            array_push($entites_array, $object_entitie); 
            
        }
        
        return $this->json(['search' => $search, 'id_territorial' => $type_territorial, 'entites' => $entites_array ]);
        
    }

    /**
     * @param Request $request
     * @Route("/personne/adresse/parents", name="les_adresses_parents")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Doctrine\DBAL\DBALException
     * Cette fonction permet de récuperer les adresses parentes d'une entité
     */
    public function getParentsEntities(Request $request) {

        // Récuperer les paramètres fournis en POST
        $id_tea             =   $request->request->get('id_tea');
        $identite_ea        =   $request->request->get('identite');
        $type_territorial   =   $request->request->get('type_territorial'); 

        // Récupérer l'ordre territorial de cette entité
        $territorial        =   $this->entityManager
                                     ->getRepository(TTerritorial::class)
                                     ->findOneBy([
                                         'fkTypeentite'         => $id_tea,
                                         'fkTypeterritorial'    => $type_territorial
                                     ]);                              

        $ordre_entite       =   $territorial->getOrdreterritorial();                              
         
        // Récupérer l'ordre  territorial maximal selon le type territorial
        $ordre_max          =   $this->entityManager
                                     ->getRepository(TTerritorial::class)
                                     ->getMaxTypeTerritorial($type_territorial); 

        $ordre_max          =   (int)$ordre_max[0]['OrdreTerritorial']; 

        $entite_admin       =   array(); 

        $entite             =   $this->entityManager
                                        ->getRepository(TEntiteAdministrative::class)
                                        ->find($identite_ea); 

        $entite_admin['fkEntiteMere']           =    $entite->getFkEntitemere();
        $entite_admin['fkTypeEntite']           =    $entite->getFkTypeentite()->getIdtypeentite(); 
        $entite_admin['libelleTypeEntite']      =    $entite->getFkTypeentite()->getIntituletypeentite();
        $entite_admin['libelleEntiteAdmin']     =    $entite->getIntituleentite();   
        $entite_admin['idEntiteAdmin']          =    $entite->getIdentite();                               
                 
    
        $all_entities       =   array();
        array_push($all_entities, $entite_admin);

        // Récupérer toutes les entités mères de l'entité
        $all = $this->getEntitesParents($entite_admin, $ordre_entite, $ordre_max, $all_entities, $type_territorial);

        // Récupérer toutes les types entités selon le type_territorial
        $types_entites      =   $this->entityManager
                                     ->getRepository(TTerritorial::class)
                                     ->getAllTypesEnties($type_territorial);
        
        return $this->json([ 'entites' => $all , 'types' => $types_entites ]);
                            
    }

    /**
     * @param $entite
     * @param $ordre
     * @param $ordre_max
     * @param $all_entites
     * @return mixed
     * Cette fonction permet de récuperer de manière recursive les entités parents
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getEntitesParents ($entite, $ordre, $ordre_max, $all_entites, $type_territorial) {
        $id_ena         =  $entite['idEntiteAdmin'];
        $id_tea         =  $entite['fkTypeEntite']; 

        //var_dump($type_territorial); 

        $entiteMere     =  $this->entityManager
                                ->getRepository(TEntiteAdministrative::class)
                                ->getEntitesMereAdmin ($id_ena, $id_tea, $type_territorial);
                                                  
        $entiteMere = array_shift($entiteMere); 

        if ($ordre < $ordre_max && isset($entiteMere)) {
            $object         =   array(); 
            
            $object['fkEntiteMere']           =    $entiteMere['Fk_EntiteMere'];
            $object['fkTypeEntite']           =    $entiteMere['Fk_TypeEntite']; 
            $object['libelleTypeEntite']      =    $entiteMere['IntituleEntite'];
            
            $object['libelleEntiteAdmin']     =    $entiteMere['IntituleEntite'];   
            $object['idEntiteAdmin']          =    $entiteMere['IdEntite']; 

            $object['libelleTypeEntite']      =    $this->entityManager
                                                        ->getRepository(TEntiteAdministrative::class)
                                                        ->find($object['idEntiteAdmin'])
                                                        ->getFkTypeentite()
                                                        ->getIntituletypeentite(); 

            $ordre = $ordre+1; 

            array_push($all_entites, $object);

            return $this->getEntitesParents($object, $ordre, $ordre_max, $all_entites, $type_territorial); 
        }

        return $all_entites; 

    }

    /**
     * @param Request $request
     * @Route("/personne/adresse/child", name="adresse_enfant")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Doctrine\DBAL\DBALException
     * Cette fonction permet de récuperer l'entite administrative enfant
     * de l'entite administrative parente fournie en paramètre
     */
    public function getDirectChild(Request $request) {
        // Récuperer les paramètres fournis en POST
        $id_entiteMere             =   $request->request->get('id_entite_mere');
        $id_territorial            =   $request->request->get('type_territorial'); 
        $type_entite               =   $request->request->get('tea');
        $direct                    =   $request->request->get('direct');

        // Récupérer l'ordre max
        $territorial               =   $this->entityManager
                                            ->getRepository(TTerritorial::class)
                                            ->findOneBy([
                                                'fkTypeentite'         => $type_entite,
                                                'fkTypeterritorial'    => $id_territorial
                                            ]);                              

        $ordre_entite              =   $territorial->getOrdreterritorial();

        $entite_admin              =   $this->entityManager
                                            ->getRepository(TEntiteAdministrative::class)
                                            ->getChildEntitesAdmin($id_entiteMere, $id_territorial, $type_entite);

        if (isset($direct)) {
            return $this->json(['entites' => $entite_admin ]);
        }

        $all = $this->getAllChild($territorial, array(), $entite_admin); 
                                    
        return $this->json(['entites' => $all ]);                                     
    }

    /**
     * @param $territorial
     * @param array $all_territorials
     * @param null $entite_admin
     * @return array
     * Cette fonction a pour but de récuperer tous les entités filles
     */
    public function getAllChild($territorial, $all_territorials = array(), $entite_admin=null) {
        $ordre_entite              =   $territorial->getOrdreterritorial();
        $type_entite               =   $territorial->getFkTypeentite(); 

        if( $ordre_entite > 1 ) {
            $object                 =   array();
            $territorial_fille      =  $this->entityManager
                                            ->getRepository(TTerritorial::class)
                                            ->findOneBy([
                                                'fkTypeentitemere'         => $type_entite,
                                                'fkTypeterritorial'        => $territorial->getFkTypeterritorial()
                                            ]);
            if (isset($entite_admin) && !empty($entite_admin)) {
                $object['entitesAdministratives'] = $entite_admin; 
            }
            $object['ordreTerritorial']     = $territorial_fille->getOrdreterritorial();
            $object['type_entite']          = $territorial_fille->getFkTypeentite();
            $object['libelleTypeEntite']    = $this->entityManager
                                                    ->getRepository(TTerritorial::class)
                                                    ->getLibelleType($territorial_fille->getFkTypeentite());
            
            array_push($all_territorials, $object); 
            
            return $this->getAllChild($territorial_fille, $all_territorials); 

        }

        return $all_territorials; 
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * Cette fonction permet d'ajouter une entité administrative
     * @Route("/personne/ajouter/entite", name="ajout_entite_administrative")
     */
    public function addEntiteAdmin(Request $request) {
        $id_entite_mere         =   $request->request->get('id_entite_mere');
        $libelle                =   $request->request->get('libelle');
        $tea                    =   $request->request->get('tea');

        $new_entite     = $this->entityManager
                                ->getRepository(TEntiteAdministrative::class)
                                ->addEntites($libelle, "", $id_entite_mere, $tea);


        return $this->json(['entite' => $new_entite, 'success' => true ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * Cette fonction permet d'ajouter une nouvelle ligne dans la table adresse
     * @Route("/personne/ajouter/adresse", name="ajout_adresse")
     */
    public function insertIntoAdresse(Request $request) {
        $fk_entite          =   $request->request->get('fk_entite');
        $numero             =   $request->request->get('numero');
        $adresse_complete   =   $request->request->get('adresse_complete');

        $new_adresse        =   new Adresse();

        $new_adresse->setEtat(1);
        $new_adresse->setParDefaut(1);
        $new_adresse->setAdresseComplete($adresse_complete);
        if ($numero) {
            $new_adresse->setNumero($numero);
        }
        $entite_admin = $this->entityManager->getRepository(TEntiteAdministrative::class)->find($fk_entite);
        $new_adresse->setFkEntite($entite_admin);
        //$new_adresse->addPersonne();

    }
}
