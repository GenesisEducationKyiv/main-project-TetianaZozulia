<?php

declare(strict_types=1);

namespace App\Model\ResourceModel;

class SubscriberResource implements ResourceInterface
{
    public function __construct(
        private string $email,
        private string $topic = 'currency'
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }
}
