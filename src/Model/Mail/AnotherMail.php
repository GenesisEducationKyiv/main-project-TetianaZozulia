<?php

declare(strict_types=1);

namespace App\Model\Mail;

use App\Model\Email;

class AnotherMail extends AbstractMail
{
    public function getFrom(): ?Email
    {
        return new Email('another.mail@gmail.com');
    }

    public function getSubject(): string
    {
        return 'Another subject';
    }

    public function getHtml(): string
    {
        return 'Another Html';
    }
}
