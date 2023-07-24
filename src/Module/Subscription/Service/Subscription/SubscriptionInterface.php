<?php

declare(strict_types=1);

namespace App\Module\Subscription\Service\Subscription;

use App\Model\Topic;
use App\Module\Subscription\Exception\DuplicateEmailException;
use App\Module\Subscription\Exception\LockBusyException;
use App\Module\Subscription\Model\Subscriber;

interface SubscriptionInterface
{
    /**
     * @throws DuplicateEmailException
     * @throws LockBusyException
     */
    public function addSubscriber(Subscriber $subscriber): void;

    public function getSubscribers(Topic $topic): array;
}
