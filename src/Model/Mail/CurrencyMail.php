<?php declare(strict_types=1);

namespace App\Model\Mail;

use App\Model\Email;

class CurrencyMail implements MailInterface
{
    private Email $to;
    private string $txt;

    public function __construct(
        private string $html,
        private string $from,
        private string $subject
    ) {
    }

    public function setTo(Email $to): void
    {
        $this->to = $to;
    }

    public function setTxt(string $txt): void
    {
        $this->txt = $txt;
    }

    public function getFrom(): ?Email
    {
        return new Email($this->from);
    }

    public function getTo(): Email
    {
        return $this->to;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getTxt(): string
    {
        return $this->txt;
    }

    public function getHtml(): string
    {
        return $this->html;
    }
}
