<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use App\Exception\NotValidTopicTypeException;
use App\Model\Mail\AnotherMail;
use App\Model\Mail\CurrencyMail;
use App\Model\Mail\MailInterface;
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
