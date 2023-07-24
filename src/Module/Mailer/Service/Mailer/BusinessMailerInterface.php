<?php

declare(strict_types=1);

namespace App\Module\Mailer\Service\Mailer;

use App\Module\Mailer\Model\Mail\MailInterface;

interface BusinessMailerInterface
{
    public function send(MailInterface $mail);

    public function batchSend(MailInterface $mail, array $emails): void;
}
