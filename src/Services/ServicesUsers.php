<?php

namespace App\Services;

use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Services users
 */
class ServicesUsers extends AbstractController
{
    private const PROFIL_PATH_IMG = 'images\profils\\';

    public function __construct(
        ManagerRegistry $manager,
        ServicesMailer $ServicesMailer,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->manager = $manager;
        $this->ServicesMailer = $ServicesMailer;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Registration and processing of user backup
     *
     * @param Users $users Vérification
     */
    public function saveValidateUsers(Users $users)
    {
        $users
            ->setIsValidate(true)
            ->setToken('');

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($users);
        $managerRegistry->flush();
    }

    /**
     * saving new password
     *
     * @param  Users $users Entity Users
     * @param  string $password New password
     */
    public function saveNewPassword(Users $users, string $password)
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $users,
            $password
        );

        $users
            ->setToken('')
            ->setPassword($hashedPassword);

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($users);
        $managerRegistry->flush();
    }
    
    /**
     * saving the token for sending the password modification email
     *
     * @param  Users $users Entity Users
     */
    public function saveTokenForgotPassword(Users $users)
    {
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        $users->setToken($token);
        
        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($users);
        $managerRegistry->flush();

        $this->sendMailForgotPassword($users, $token);
    }

    /**
     * Registration and processing of user backup
     *
     * @param Object $datas_file_path Profile picture data
     */
    public function saveNewUsers(Users $users, Object $datas_file_path)
    {
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $file = md5(uniqid()) . '.' . $datas_file_path->guessExtension();

        $filesystem = new Filesystem();
        $filesystem->mkdir(self::PROFIL_PATH_IMG);
        if (!$filesystem->exists($file)) {
            $datas_file_path->move(self::PROFIL_PATH_IMG, $file);
        }
        
        $hashedPassword = $this->passwordHasher->hashPassword(
            $users,
            $users->getPassword()
        );

        $users
            ->setToken($token)
            ->setPassword($hashedPassword)
            ->setFilePath($file);

        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($users);
        $managerRegistry->flush();

        $this->sendMailRegistration($users, $token);
    }
    
    /**
     * Send email after user registration
     *
     * @param  Users $users Entity Users
     * @param  string $token unique token to verify email address
     */
    private function sendMailRegistration(Users $users, string $token)
    {
        $to = $users->getEmail();
        $subject = 'Snowtricks validation du compte';
        $adress_token = $this->generateUrl(
            'validation_mail',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $html = "<p>Veuillez cliquer sur le lien : $adress_token pour valider votre compte</p>";

        $this->ServicesMailer->send($to, $subject, $html);
    }

    /**
     * Send email before change password
     *
     * @param  Users $users Entity users
     * @param  string $token unique token to verify email address
     */
    private function sendMailForgotPassword(Users $users, string $token)
    {
        $to = $users->getEmail('email');
        $subject = 'Snowtricks modification du mot de passe';
        $adress_token = $this->generateUrl(
            'app_change_password',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $html = "<p>Veuillez cliquer sur le lien : $adress_token pour modifier votre mot de passe</p>";

        $this->ServicesMailer->send($to, $subject, $html);
    }
}
