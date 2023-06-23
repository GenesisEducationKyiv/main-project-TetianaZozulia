<?php declare(strict_types=1);

namespace App\Model\ResourceModel;

class SubscriberResource implements ResourceInterface
{
    public function __construct(
        private string $email,
        private string $topic = 'currency'
    ) {
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }
}
