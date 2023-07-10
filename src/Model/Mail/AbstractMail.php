<?php

declare(strict_types=1);

namespace App\Model\Mail;

use App\Model\Email;

abstract class AbstractMail implements MailInterface
{
    private Email $to;
    private string $txt;

    abstract public function getFrom(): ?Email;
    abstract public function getSubject(): string;
    abstract public function getHtml(): string;

    public function setTo(Email $to): void
    {
        $this->to = $to;
    }

    public function setTxt(string $txt): void
    {
        $this->txt = $txt;
    }

    public function getTo(): Email
    {
        return $this->to;
    }

    public function getTxt(): string
    {
        return $this->txt;
    }
}
