<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use App\Model\Mail\MailInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Model\Email as EmailModel;

class Mailer implements BusinessMailerInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {
    }

    public function send(MailInterface $mail): void
    {
        try {
            $email = (new Email())
                ->from($mail->getFrom()->getEmail())
                ->to($mail->getTo()->getEmail())
                ->subject($mail->getSubject())
                ->text($mail->getTxt())
                ->html($mail->getHtml());

            $this->mailer->send($email);
        } catch (\Throwable $exception) {
            $this->logger->info(sprintf(
                'Sending message "%s" to email %s was failed',
                $mail->getSubject(),
                $mail->getTo()->getEmail()
            ));
        }
    }

    public function batchSend(MailInterface $mail, array $emails): void
    {
        foreach ($emails as $email) {
            $mail->setTo(new EmailModel($email));
            $this->send($mail);
        }
    }
}
