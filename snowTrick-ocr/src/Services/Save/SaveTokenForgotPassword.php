<?php

namespace App\Services\Save;

use App\Entity\Users;
use App\Services\Mail\SendMailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SaveTokenForgotPassword extends AbstractController
{
    public function __construct(
        ManagerRegistry $manager,
        SendMailer $mailer
    ) {
        $this->manager = $manager;
        $this->mailer = $mailer;
    }
    
    /**
     * saving the token for sending the password modification email
     *
     * @param  Users $users Entity Users
     */
    public function save(Users $users)
    {
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        $users->setToken($token);
        
        $managerRegistry = $this->manager->getManager();
        $managerRegistry->persist($users);
        $managerRegistry->flush();

        $this->sendMailForgotPassword($users, $token);
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

        $this->mailer->send($to, $subject, $html);
    }
}
