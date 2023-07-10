<?php

declare(strict_types=1);

namespace App\Service\BusinessCase;

use App\Exception\DuplicateEmailException;
use App\Exception\LockBusyException;
use App\Model\Email;
use App\Model\ResourceModel\SubscriberResource;
use App\Model\SubscriberModel;
use App\Model\Topic;
use App\Service\Subscription\SubscriptionInterface;

class AddSubscribersCase
{
    public function __construct(private SubscriptionInterface $subscription)
    {
    }

    /**
     * @throws DuplicateEmailException
     * @throws LockBusyException
     */
    public function execute(SubscriberResource $resource): void
    {
        $subscriber = new SubscriberModel(
            new Email($resource->getEmail()),
            new Topic($resource->getTopic())
        );
        $this->subscription->addSubscriber($subscriber);
    }
}
