<?php

namespace App\RabbitMQ\MessageHandler;

use App\Services\Mail;
use App\RabbitMQ\Message\MailNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MailNotificationHandler implements MessageHandlerInterface
{
    //$this->dispatchMessage(new MailNotification($task->getDescription(), $task->getId(), $task->getUser()->getEmail()));

    public function __construct(private Mail $mail)
    {
    }

    public function __invoke(MailNotification $information)
    {
        $this->mail->sendMail($information);
    }
}
