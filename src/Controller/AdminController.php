<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Fonction;
use App\Entity\Personne;
use App\Form\ActiviteType;
use App\Form\FonctionType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    private $entityManager;
    private $security;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, Security $security, SessionInterface $session)
    {
        $this->entityManager    =   $entityManager;
        $this->session          =   $session;
        $this->security         =   $security;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $data   = array();
        $data['page'] = "Admin";

        return $this->render('admin/index.html.twig', $data );
    }

    /**
     * @Route("/admin/fonctions", name="fonctions")
     * @return Response
     * Cette fonction liste toutes les fonctions en base de donnée
     */
    public function getAllFonctions(Request $request)
    {
        $data   =   array();

        $fonctions              =   $this->entityManager->getRepository(Fonction::class)->findAll();
        $data['fonctions']      =   $fonctions;
        $data['page']           =   "Liste des fonctions";

        $fonction   = new Fonction();
        $form = $this->createForm(FonctionType::class, $fonction)
                     ->add('save_fonction', SubmitType::class, ['label' => 'Enregistrer']);

        $edit_form = $this->createForm(FonctionType::class, $fonction)
                          ->add('id_fonction', HiddenType::class, ['mapped' => false ])
                          ->add('edit_fonction', SubmitType::class, ['label' => 'Modifier'])
                          ->add('reset', ResetType::class, ['label' => 'Annuler', 'attr' => ['class' => 'btn-danger']]);

        $form->handleRequest($request);
        $edit_form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fonction = $form->getData();

            $this->entityManager->persist($fonction);
            $this->entityManager->flush();

            return $this->redirectToRoute('fonctions');
        }

        if($edit_form->isSubmitted() && $edit_form->isValid()) {
            $id = (int)$edit_form->get('id_fonction')->getData();

            $fonction   = $this->entityManager->getRepository(Fonction::class)->find($id);
            $fonction->setNom($edit_form->get('nom')->getData());
            $this->entityManager->persist($fonction);
            $this->entityManager->flush();

            return $this->redirectToRoute('fonctions');

        }
        $data['form']       = $form->createView();
        $data['edit_form']  = $edit_form->createView();

        return $this->render('admin/fonctions.html.twig', $data);
    }

    public function addFonction (Request $request)
    {
        $data   =   array();
        $fonction   =   new Fonction();

        $form = $this->createForm(FonctionType::class, $fonction);

        if ($form->isSubmitted() && $form->isValid()) {
            $fonction = $form->getData();

            $this->entityManager->persist($fonction);
            $this->entityManager->flush();

            return $this->redirectToRoute('fonctions');
        }

        return $this->render('admin/new-fonction.html');
    }

    /**
     * @param Fonction $fonction
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/admin/fonctions/remove/{id}", name="remove_fonction")
     */
    public function removeFunction (Fonction $fonction) {
        $this->entityManager->remove($fonction);
        $this->entityManager->flush();

        return $this->json(['deleted' => true ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/personnes", name="toutes_les_personnes")
     */
    public function getAllPersonnesView () {
        $data = array();
        $data['page']   =   "Toutes les personnes";

        return $this->render('admin/personnes.html.twig', $data);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/admin/personnes/data", name="toutes_les_personnes_json")
     */
    public function getAllPersonnesAdmin() {
        $encoder    =   new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer =   new Serializer([$normalizer], [$encoder]);
        $personnes            = $this->entityManager->getRepository(Personne::class)->findAll();

        $jsonContent    =   $serializer->normalize($personnes, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'utilisateur','sexe','profession', 'lieu_de_naissance', 'date_de_naissance', 'etat_civil', 'lieuDeNaissance', 'dateDeNaissance','nationalite','NIF'
            ]
        ]);

        return $this->json(["personnes" => $jsonContent ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/activites", name="les_secteurs_activites")
     */
    public function getAllActivities(Request $request) {
        $data                   =   array();
        $data['page']           =   "Liste des secteurs d'activités";
        $activites              =   $this->entityManager->getRepository(Activite::class)->findAll();
        $data['activites']      =   $activites;
        $activite               =   new Activite();

        $form                   =   $this->createForm(ActiviteType::class, $activite)
                                        ->add('save_activites', SubmitType::class, ['label' => 'Enregistrer']);

        $edit_form =    $this->createForm(ActiviteType::class, $activite)
                            ->add('id_activite', HiddenType::class, ['mapped' => false ])
                            ->add('edit_activite', SubmitType::class, ['label' => 'Modifier'])
                            ->add('reset', ResetType::class, ['label' => 'Annuler', 'attr' => ['class' => 'btn-danger']]);

        $form->handleRequest($request);
        $edit_form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $activite = $form->getData();

            $this->entityManager->persist($activite);
            $this->entityManager->flush();

            return $this->redirectToRoute('les_secteurs_activites');
        }

        if($edit_form->isSubmitted() && $edit_form->isValid()) {
            $id = (int)$edit_form->get('id_activite')->getData();

            $activite   = $this->entityManager->getRepository(Activite::class)->find($id);
            $activite->setNom($edit_form->get('nom')->getData());
            $this->entityManager->persist($activite);
            $this->entityManager->flush();

            return $this->redirectToRoute('les_secteurs_activites');

        }

        $data['form']       =   $form->createView();
        $data['edit_form']  =   $edit_form->createView();

        return $this->render('admin/activites.html.twig', $data);
    }
}
