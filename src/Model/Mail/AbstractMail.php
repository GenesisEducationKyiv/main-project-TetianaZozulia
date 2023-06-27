<?php declare(strict_types=1);

namespace App\Model\Mail;

use App\Model\Email;

abstract class AbstractMail implements MailInterface
{
    private Email $to;
    private string $txt;

    public function setTo(Email $to): void
    {
        $this->to = $to;
    }

    public function setTxt(string $txt): void
    {
        $this->txt = $txt;
    }

    abstract public function getFrom(): ?Email;


    public function getTo(): Email
    {
        return $this->to;
    }

    abstract public function getSubject(): string;

    public function getTxt(): string
    {
        return $this->txt;
    }

    abstract public function getHtml(): string;
}
