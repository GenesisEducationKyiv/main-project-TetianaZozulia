<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use App\Model\Mail\MailInterface;
use App\Model\Topic;

interface MailFactoryInterface
{
    public function create(Topic $topic): MailInterface;
}
