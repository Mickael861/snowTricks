<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use App\Services\ServicesUsers;
use App\Services\ServicesMailer;
use App\Services\ServicesSecurity;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Manage user authentication
 */
class SecurityController extends AbstractController
{
    public function __construct(
        ManagerRegistry $manager,
        ServicesMailer $ServicesMailer,
        UserPasswordHasherInterface $passwordHasher,
        ServicesUsers $servicesUsers,
        ServicesSecurity $servicesSecurity
    ) {
        $this->manager = $manager;
        $this->mailer = $ServicesMailer;
        $this->passwordHasher = $passwordHasher;
        $this->servicesUsers = $servicesUsers;
        $this->servicesSecurity = $servicesSecurity;
    }

    /**
     * @Route("/signup", name="security_registration")
     *
     * User registration
     */
    public function registrationAction(Request $request): Response
    {
        if (!is_null($this->getUser())) {
            $this->addFlash('errors', 'Vous êtes déjà connecté, déconnectez-vous pour pouvoir créer un compte');

            return $this->redirectToRoute('app_figures');
        }

        $users = new Users;

        $formLogin = $this->createForm(RegistrationType::class, $users);
        $formLogin->handleRequest($request);
        
        if ($formLogin->isSubmitted() && $formLogin->isValid()) {
            $file_path = $formLogin->get('file_path')->getData();
            $this->servicesUsers->saveNewUsers($users, $file_path);
            
            $this->addFlash('success', "Un email vous a été envoyé pour vérifier votre compte");

            return $this->redirectToRoute('app_figures');
        }

        return $this->render(
            'security/registration.html.twig',
            [
            'formLogin' => $formLogin->createView(),
            ]
        );
    }

    /**
     * @Route("/validation/{token}", name="validation_mail")
     *
     * Manage the validation of the token sent by email to the registered user
     *
     * @param string $token The token sent by email to the user
     */
    public function valdationTokenMailAction(string $token): response
    {
        $repository = $this->manager->getRepository(Users::class);
        $addFlash = [];
        $redirect = null;

        $users = $repository->findOneBy([
            'token' => $token
        ]);

        if (!empty($users) && !empty($users->getToken()) && !$users->isIsValidate()) {
            $this->servicesUsers->saveValidateUsers($users);

            $addFlash['success'] = 'Votre compte a été accepté, vous pouvez désormais vous connecter';
            $redirect = 'app_login';
        }

        $addFlash['errors'] = 'Une erreur c\'est produite, validation du compte impossible';
        $redirect = 'app_figures';

        $this->addFlash(key($addFlash), $addFlash[key($addFlash)]);

        return $this->redirectToRoute($redirect);
    }

    /**
     * @Route("/forgot/password", name="app_forgot_password")
     *
     * Send email to reset password
     */
    public function forgotPasswordAction(Request $request): response
    {
        $user_name = trim($request->request->get('user_name'));
        $is_valide = false;
        $is_submited = false;
        $errors = null;

        if ($request->request->get('user_name') !== null) {
            $is_submited = true;
            if (!empty($user_name)) {
                $this->servicesUsers->managementBackupForgottenPassword($user_name, $is_valide);

                if ($is_valide) {
                    return $this->redirectToRoute('app_figures');
                }

                $errors = 'Le nom d\'utilisateur est introuvable';
            } else {
                $errors = 'Le nom d\'utilisateur est obligatoire';
            }
        }
        
        return $this->render(
            'security/forgot_password.html.twig',
            [
                'is_valide'   => $is_valide,
                'is_submited' => $is_submited,
                'errors'      => $errors
            ]
        );
    }

    /**
     * @Route("/change/password/{token}", name="app_change_password")
     *
     * Change password
     *
     * @param  string $token Token sent by email to change the password
     */
    public function changePasswordAction(string $token, Request $request): response
    {
        $password = trim($request->request->get('password'));
        $is_valide = true;
        $errors = null;
        $addFlash = [];

        $this->servicesSecurity->changePasswordManagement(
            $token,
            $password,
            $addFlash,
            $is_valide,
            $errors
        );
        
        if (!empty($addFlash)) {
            $this->addFlash(key($addFlash), $addFlash[key($addFlash)]);

            return $this->redirectToRoute('app_figures');
        }

        return $this->render(
            'security/change_password.html.twig',
            [
                'is_valide'   => $is_valide,
                'errors'      => $errors
            ]
        );
    }

    /**
     * @Route("/login", name="app_login")
     *
     * User Login
     */
    public function loginAction(AuthenticationUtils $authenticationUtils): response
    {
        if (!is_null($this->getUser())) {
            $this->addFlash('errors', 'Vous êtes déjà connecté');

            return $this->redirectToRoute('app_figures');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        if (!empty($error)) {
            $error = 'Le nom d\'utilisateur ou le mot de passe est incorrecte';
        }
        
        return $this->render(
            'security/login.html.twig',
            [
            'error' => $error,
            ]
        );
    }
    
    /**
     * @Route("/logout", name="app_logout")
     *
     * User logout
     */
    public function logoutAction(): void
    {
    }
}
