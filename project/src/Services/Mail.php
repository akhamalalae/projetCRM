<?php

namespace App\Services;

use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

class Mail
{
    public function __construct(private EntityManagerInterface $em, private MailerInterface $mailer)
    {
    }

    public function sendMail($information)
    {
        $email = (new Email())
        ->from($information->getFrom())
        ->to('admin@my.io')
        ->subject($information->getId() . ' - ' . $information->getFrom())
        ->html('<p>' . $information->getDescription() . '</p>');

        $this->mailer->send($email);
    }
}