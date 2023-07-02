<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use App\Model\Mail\MailInterface;

interface BusinessMailerInterface
{
    public function send(MailInterface $mail);

    public function batchSend(MailInterface $mail, array $emails): void;
}
