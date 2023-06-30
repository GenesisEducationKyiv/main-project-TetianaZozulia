<?php declare(strict_types=1);

namespace App\Model;

class SubscriberModel
{
    public function __construct(
        private Email $email,
        private Topic $topic
    ) {
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Topic
     */
    public function getTopic(): Topic
    {
        return $this->topic;
    }
}
