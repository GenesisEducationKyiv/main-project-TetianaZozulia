<?php declare(strict_types=1);

namespace App\Model\Mail;

use App\Model\Email;

class CurrencyMail extends Mail
{
    public function __construct(Email $to, string $txt, string $html, ?Email $from = null, ?string $subject = null)
    {
        $from = new Email('test@test.test');
        $subject = 'Current currency for USD vs BTC';
        parent::__construct($to, $txt, $html, $from, $subject);
    }

}
