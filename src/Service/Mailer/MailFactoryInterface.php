<?php declare(strict_types=1);

namespace App\Service\Mailer;

use App\Model\Mail\MailInterface;
use App\Model\Topic;

interface MailFactoryInterface
{
    public const MAIL_NAMESPACE = 'App\Model\Mail\\';

    public function create(Topic $topic): MailInterface;
}
