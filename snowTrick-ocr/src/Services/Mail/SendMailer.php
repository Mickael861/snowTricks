<?php

namespace App\Services\Mail;

use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Used to send emails using the mailerInterface
 */
class SendMailer extends AbstractController
{
    private const EMAIL_SERVER = 'hello@example.com';

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send email
     *
     * @param  string $to Recipient
     * @param  string $subject Object
     * @param  string $html content email
     * @return void
     */
    public function send(string $to, string $subject, string $html)
    {
        $email = (new Email())
        ->from(self::EMAIL_SERVER)
        ->to($to)
        ->subject($subject)
        ->html($html);

        $this->mailer->send($email);
    }
}
