<?php

declare(strict_types=1);

namespace App\Module\Mailer\Model\Mail;

use App\Model\Email;

interface MailInterface
{
    public function setTo(Email $to): void;

    public function setTxt(string $txt): void;

    public function getFrom(): ?Email;

    public function getTo(): Email;

    public function getSubject(): ?string;

    public function getTxt(): string;

    public function getHtml(): string;
}
