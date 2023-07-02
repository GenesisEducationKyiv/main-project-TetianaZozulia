<?php

declare(strict_types=1);

namespace App\Service\Subscription;

use App\Model\SubscriberModel;

interface SubscriptionInterface
{
    public function addSubscriber(SubscriberModel $subscriber): void;
}
