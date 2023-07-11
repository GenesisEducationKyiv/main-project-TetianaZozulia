<?php

declare(strict_types=1);

namespace App\Module\Mailer\Service\Mailer;

use App\Exception\NotValidTopicTypeException;
use App\Module\Mailer\Model\Mail\AnotherMail;
use App\Module\Mailer\Model\Mail\CurrencyMail;
use App\Module\Mailer\Model\Mail\MailInterface;
use App\Model\Topic as TopicModel;
use App\Enum\Topic;

class MailFactory implements MailFactoryInterface
{
    public function create(TopicModel $topic): MailInterface
    {
        return match ($topic->getName()) {
            Topic::Currency->value => new CurrencyMail(),
            Topic::Another->value => new AnotherMail(),
            default => throw new NotValidTopicTypeException($topic->getName()),
        };
    }
}
