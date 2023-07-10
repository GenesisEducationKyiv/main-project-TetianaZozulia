<?php

declare(strict_types=1);

namespace App\Service\Subscription;

use App\Exception\DuplicateEmailException;
use App\Exception\LockBusyException;
use App\Model\SubscriberModel;

interface SubscriptionInterface
{
    /**
     * @throws DuplicateEmailException
     * @throws LockBusyException
     */
    public function addSubscriber(SubscriberModel $subscriber): void;
}
