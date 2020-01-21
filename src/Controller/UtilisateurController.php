<?php

namespace App\Controller;

use App\Entity\Fonction;
use App\Entity\Personne;
use App\Entity\PersonneMorale;
use App\Entity\PersonnePhysique;
use App\Entity\User;
use App\Entity\Utilisateur;
use App\Form\PersonneMoraleType;
use App\Form\PersonnePhysiqueType;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class UtilisateurController
 * @package App\Controller
 */
class UtilisateurController extends AbstractController
{
    private $entityManager;
    private $mailer;
    private  $session;
    private $security;
    private $authenticatorHandler;

    public function __construct(SessionInterface $session, MailerInterface $mailer, EntityManagerInterface $entityManager, Security $security, GuardAuthenticatorHandler $authenticator)
    {
        $this->entityManager            =   $entityManager;
        $this->mailer                   =   $mailer;
        $this->session                  =   $session;
        $this->security                 =   $security;
        $this->authenticatorHandler     =   $authenticator;
    }

    /**
     * @Route("/login", name="app_login")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param TokenInterface $token
     * @param $providerKey
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request, $providerKey=null): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws \Exception
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator): Response
    {

        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_USER']);
            $user->setEtat(0);
            $user->setDateCreation(new \DateTime());
            $code_validation    = $this->generateRandomCode();
            $user->setCodeValidation($code_validation);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Send an email
            $this->sendMail($user, $form->get('plainPassword')->getData(), $code_validation, $request);

            return $this->redirectToRoute('message_confirmation');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @param Utilisateur $user
     * @param $password
     * @param Request $request
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * Cette fonction permet d'envoyer un mail avec un code de validation permettant d'activer le compte de l'utilisateur
     */
    public function sendMail(Utilisateur $user, $password, $code_validation, Request $request) {

        $host = $request->getHttpHost();

        $email =    (new TemplatedEmail())
                    ->from('jkazdev@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Bienvenue à E-Urban')
                    ->htmlTemplate('mail/signup-mail.html.twig')
                    ->context([
                        'user' => $user,
                        'code' => $code_validation,
                        'plain_password' => $password,
                        'host' => $host
                    ]);

        $this->mailer->send($email);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param GuardAuthenticatorHandler $guardHandler
     * @param AppCustomAuthenticator $authenticator
     * @return Response|null
     * Cette fonction change le statut de l'utilisateur à actif et l'authentifie dans le système directement
     * @Route("/activation/utilisateur/{id}", name="user_activation")
     */
    public function activerUtilisateur(Request $request, $id, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator)
    {
        $user = $this->entityManager->getRepository(Utilisateur::class)->find($id);
        $data   =   array();
        $data['page']   =   "Activation de compte";
        $data['error'] = null;
        if (!$user) {
            throw $this->createNotFoundException("Pas d'utilisateur existant pour cet $id");
        }

        if ($request->isMethod('POST')) {
            $code_validation = $request->request->get('code');
            if ($user->getCodeValidation() == $code_validation ) {

                $user->setEtat(1);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }
            $this->addFlash(
                'error',
                'Votre code de validation est incorrecte'
            );
        }

        return $this->render('utilisateur/activation-compte.html.twig', $data );
    }

    /**
     * @param Request $request
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/profil", name="mon_profil")
     * @return Response
     * @throws \Exception
     */
    public function getUserProfile (Request $request) {
        $data           =   array();

        $user           =   $this->getUser();
        $utilisateur    =   $this->entityManager->getRepository(Utilisateur::class)->find($user->getId());
        //$is_Physique    =   $utilisateur->getPersonne();
        $form                       =   null;
        $data['date_naissance']     =   null;
        //if( $is_Physique == 1) {
        $personne               = $this->entityManager->getRepository(Personne::class)->findOneBy(['email' => $user->getEmail()]);
        if (isset($personne)) {
            $date_naissance         = $personne->getDateDeNaissance()->format("Y-m-d");
            $data["date_naissance"] = $date_naissance;
        }
        $form                   = $this->createForm(PersonnePhysiqueType::class, $personne);
        $data['form']           = $form->createView();
        //} else {
        /*$morale                 = $utilisateur->getPersonneMorale();
        $form_morale            = $this->createForm(PersonneMoraleType::class, $morale);
        $data['formMorale']     = $form_morale->createView();*/
        //}

        $form->handleRequest($request);
        //->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $personne_physique = $form->getData();
            $date_naissance = new \DateTime($form->get('date_de_naissance')->getData());
            $personne_physique->setDateDeNaissance($date_naissance);
            $utilisateur->setPersonnePhysique($personne_physique);
            $this->entityManager->persist($utilisateur);
            $this->entityManager->persist($personne_physique);

            $this->entityManager->flush();
            return $this->redirectToRoute('mon_profil');
        }

        /*if ($form_morale->isSubmitted() && $form_morale->isValid()) {
            $personne_morale  = $form_morale->getData();
            $utilisateur->setPersonneMorale($personne_morale);
            $this->entityManager->persist($utilisateur);
            $this->entityManager->persist($personne_morale);

            $this->entityManager->flush();
            return $this->redirectToRoute('mon_profil');
        }*/

        $data['user'] = $user;
        $data['page'] = "Mon Profil";

        return $this->render('utilisateur/profil.html.twig', $data );
    }

    /**
     * @Route("/parametres/compte", name="user_account")
     * @return Response
     */
    public function getAccountUser() {
        $data = array();
        $data['page'] = 'Paramètres du compte';
        return $this->render('utilisateur/account-user.html.twig', $data );
    }

    /**
     * @Route("/parametres/mot-de-passe", name="change_password")
     * @param Request $request
     * @param AppCustomAuthenticator $authenticator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * Cette fonction permet de changer le mot de passe du compte de l'utilisateur
     */
    public function changePassword(Request $request, AppCustomAuthenticator $authenticator, UserPasswordEncoderInterface $passwordEncoder) {
        $data = array();
        $data['page'] = "Changer le mot de passe";
        $data['user'] = $this->entityManager->getRepository(Utilisateur::class)->find($this->getUser()->getId());

        // Create a form
        $passwordtab = array(
            "password" => '',
            "new_password" => ''
        );
        $form_password = $this->createFormBuilder($passwordtab)
            ->add('password', PasswordType::class, ['label' => 'Votre ancien mot de passe'])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entre votre nouveau mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Vérifiez votre mot de passe']
            ])
            ->add('save', SubmitType::class, ['label' => 'Changer le mot de passe', 'attr' => ['class' => 'btn btn-brand btn-bold']])
            ->add('reset', ResetType::class, ['label' => 'Annuler', 'attr' => ['class' => 'btn btn-secondary']])
            ->getForm();

        $form_password->handleRequest($request);

        if ($form_password->isSubmitted() && $form_password->isValid()) {
            $old_password = $form_password->get('password')->getData();
            $credentials['password'] = $old_password;
            $user = $this->entityManager->getRepository(Utilisateur::class)->find($this->getUser()->getId());

            if($authenticator->checkCredentials($credentials, $user)) {
                $new_password = $form_password->get('plainPassword')->getData();
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $new_password
                    )
                );
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->addFlash(
                    'success',
                    'Votre mot de passe a été modifié avec succès'
                );
            }
            $this->addFlash(
                'error',
                'Une erreur est survenue lors du changment de mot de passe'
            );
        }

        $data['form'] = $form_password->createView();

        return $this->render('utilisateur/password-settings.html.twig', $data );
    }

    /**
     * @Route("/message/confirmation", name="message_confirmation")
     */
    public function showMessagePage() {
        return $this->render('utilisateur/message-confirmation.html.twig');
    }

    public function generateRandomCode () : string {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $plainPassword = substr(str_shuffle($permitted_chars), 0, 10);

        return $plainPassword;
    }

}
