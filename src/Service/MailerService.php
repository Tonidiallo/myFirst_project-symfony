<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $replyTo;
    public function __construct(private MailerInterface $mailer) {
        
    }
    public function sendEmail(
        $to = 'antoinediallo@gmail.com',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = 'Time for Symfony Mailer!'
    ): void
    {
        $email = (new Email())
            ->from('antoinediallo@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->priority(Email::PRIORITY_HIGH)
            //->text('Sending emails is fun again!')

            //->replyTo($this->replyTo)
            ->subject($subject)
            ->html($content);
             $this->mailer->send($email);
        // ...
    }
}