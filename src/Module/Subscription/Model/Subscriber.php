<?php

declare(strict_types=1);

namespace App\Module\Subscription\Model;

use App\Model\Email;
use App\Model\Topic;

class Subscriber
{
    public function __construct(
        private Email $email,
        private Topic $topic
    ) {
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getTopic(): Topic
    {
        return $this->topic;
    }
}
