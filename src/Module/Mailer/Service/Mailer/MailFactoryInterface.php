<?php

declare(strict_types=1);

namespace App\Module\Mailer\Service\Mailer;

use App\Module\Mailer\Model\Mail\MailInterface;
use App\Model\Topic;

interface MailFactoryInterface
{
    public function create(Topic $topic): MailInterface;
}
