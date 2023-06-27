<?php declare(strict_types=1);

namespace App\Model\Mail;

use App\Model\Email;

class CurrencyMail extends AbstractMail
{
    public function getFrom(): ?Email
    {
        return new Email('tanuha.zoz@gmail.com');
    }

    public function getSubject(): string
    {
        return 'Current currency for USD vs BTC';
    }

    public function getHtml(): string
    {
        return 'Current currency for USD vs BTC';
    }
}
