<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/utilisateur/normal", name="user_normal")
     */
    public function test()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function homePage()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'page'  => "HomePage"
        ]);
    }
}
