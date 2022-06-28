<?php

namespace App\Services\Save;

use App\Entity\Users;
use DateTimeImmutable;
use App\Services\Mail\SendMailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SaveUser extends AbstractController
{
    private const PROFIL_PATH_IMG = 'images\profils\\';

    public function __construct(
        ManagerRegistry $manager,
        SendMailer $mailer,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->manager = $manager;
        $this->mailer = $mailer;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Registration and processing of user backup
     *
     * @param Object $datas_file_path Profile picture data
     */
    public function save(Users $users, Object $datas_file_path)
    {
        $date = new DateTimeImmutable();
        $date->format('Y-m-d H:m:s');
        
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        $file = md5(uniqid()) . '.' . $datas_file_path->guessExtension();
        $datas_file_path->move(self::PROFIL_PATH_IMG, $file);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $users,
            $users->getPassword()
        );

        $users
            ->setToken($token)
            ->setPassword($hashedPassword)
            ->setFilePath($file)
            ->setCreatedAt($date);

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

        $this->mailer->send($to, $subject, $html);
    }
}
