<?php

namespace App\Mailer;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Send an email
 */
class Mail
{
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send email to user after registration
     *
     * @param  string $from sender
     * @param  string $to Recipient
     * @param  string $subject Object
     * @param  string $html content email
     * @return void
     */
    public function sendMail(string $from, string $to, string $subject, string $html)
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($html);

        $this->mailer->send($email);
    }
}
