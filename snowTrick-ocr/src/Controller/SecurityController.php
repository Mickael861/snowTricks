<?php

namespace App\Controller;

use App\Entity\Users;
use DateTimeImmutable;
use App\Mailer\Mail;
use App\Form\RegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Manage user authentication
 */
class SecurityController extends AbstractController
{
    public function __construct(
        ManagerRegistry $manager,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ) {
        $this->manager = $manager;
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/signup", name="security_registration")
     *
     * User registration
     */
    public function registration(Request $request): Response
    {
        $users = new Users;

        $this->formLogin = $this->createForm(RegistrationType::class, $users);
        $this->formLogin->handleRequest($request);
        
        if ($this->formLogin->isSubmitted() && $this->formLogin->isValid()) {
            $this->saveUsers($users);
            
            $email = $this->formLogin->get('email')->getData();
            $this->addFlash('success', "Un email vous a été envoyé à l'adresse ($email) pour vérifier votre compte");

            return $this->redirectToRoute('app_figures');
        }

        return $this->render(
            'security/registration.html.twig',
            [
            'formLogin' => $this->formLogin->createView(),
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
    public function valdationTokenMail(string $token): response
    {
        $users = Users::class;
        $repository = $this->manager->getRepository($users);
        $addFlash = [];
        $redirect = null;

        $user = $repository->findOneBy([
            'token' => $token
        ]);

        if (!empty($user) && !empty($user->getToken()) && !$user->isIsValidate()) {
            $user
                ->setIsValidate(true)
                ->setToken('');

            $managerRegistry = $this->manager->getManager();
            $managerRegistry->persist($user);
            $managerRegistry->flush();

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
    public function forgotPassword(Request $request): response
    {
        $request = Request::createFromGlobals();
        $user_name = trim($request->request->get('user_name'));
        $is_valide = false;
        $is_submited = false;
        $errors = null;

        if ($request->request->get('user_name') !== null) {
            $is_submited = true;
            if (!empty($user_name)) {
                $users = Users::class;
                $repository = $this->manager->getRepository($users);
        
                $user = $repository->findOneBy([
                    'user_name' => $user_name
                ]);

                if (!empty($user)) {
                    $is_valide = true;
                    $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

                    $user->setToken($token);
                    
                    $managerRegistry = $this->manager->getManager();
                    $managerRegistry->persist($user);
                    $managerRegistry->flush();

                    $from = $this->getParameter('MAILER_FROM');
                    $to = $user->getEmail('email');
                    $subject = 'Snowtricks modification du mot de passe';
                    $adress_token = "http://localhost:8000/change/password/$token";
                    $html = "<p>Veuillez cliquer sur le lien : $adress_token pour modifier votre mot de passe</p>";
            
                    (new Mail($this->mailer))->sendMail($from, $to, $subject, $html);

                    $this->addFlash('success', "Un email vous a été envoyé pour réinitialiser votre mot de passe");

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
    public function changePassword(string $token): response
    {
        $request = Request::createFromGlobals();
        $password = trim($request->request->get('password'));
        $is_valide = false;
        $is_submited = false;
        $errors = null;
        $addFlash = [];

        if (!empty($token)) {
            $users = Users::class;
            $repository = $this->manager->getRepository($users);
    
            $user = $repository->findOneBy([
                'token' => $token
            ]);
            
            if (!empty($user)) {
                if ($request->request->get('password') !== null) {
                    $is_submited = true;
                    if (!empty($password)) {
                        $is_valide = true;
                        $hashedPassword = $this->passwordHasher->hashPassword(
                            $user,
                            $password
                        );
        
                        $user
                            ->setToken('')
                            ->setPassword($hashedPassword);
        
                        $managerRegistry = $this->manager->getManager();
                        $managerRegistry->persist($user);
                        $managerRegistry->flush();
        
                        $addFlash['success'] = 'Votre mot de passe a été correctement modifié';
                    } else {
                        $errors = 'Le mot de passe est obligatoire';
                    }
                }
            } else {
                $addFlash['errors'] = 'Une erreur c\'est produite, validation du compte impossible';
            }
        }
        
        if (!empty($addFlash)) {
            $this->addFlash(key($addFlash), $addFlash[key($addFlash)]);

            return $this->redirectToRoute('app_figures');
        }

        return $this->render(
            'security/change_password.html.twig',
            [
                'is_valide'   => $is_valide,
                'is_submited' => $is_submited,
                'errors'      => $errors
            ]
        );
    }

    /**
     * @Route("/login", name="app_login")
     *
     * User Login
     */
    public function login(): response
    {
        return $this->render(
            'security/login.html.twig',
            []
        );
    }
    
    /**
     * Registration and processing of user backup
     *
     * @param  object $users The user who registers
     */
    private function saveUsers(object $users)
    {
        $date = new DateTimeImmutable();
        $date->format('Y-m-d H:m:s');

        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        $image = $this->formLogin->get('file_path')->getData();
        $file = md5(uniqid()) . '.' . $image->guessExtension();
        $image->move($this->getParameter('PROFIL_PATH_IMG'), $file);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $users,
            $this->formLogin->get('password')->getData()
        );

        $users
            ->setToken($token)
            ->setPassword($hashedPassword)
            ->setFilePath($file)
            ->setCreatedAt($date);

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($users);
        $managerRegistry->flush();

        $from = $this->getParameter('MAILER_FROM');
        $to = $this->formLogin->get('email')->getData();
        $subject = 'Snowtricks validation du compte';
        $adress_token = "http://localhost:8000/validation/$token";
        $html = "<p>Veuillez cliquer sur le lien : $adress_token pour valider votre compte</p>";

        (new Mail($this->mailer))->sendMail($from, $to, $subject, $html);
    }
}
