<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class DemandeController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class DemandeController extends AbstractController
{
    private $security;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, Security $security) {
        $this->security         =   $security;
        $this->entityManager    =   $entityManager;
    }
    /**
     * @Route("/demandes", name="demande_permis")
     */
    public function index()
    {
        $data = array();
        $data['page']  = "Demande de documents";

        return $this->render('demande/index.html.twig', $data );
    }
}
