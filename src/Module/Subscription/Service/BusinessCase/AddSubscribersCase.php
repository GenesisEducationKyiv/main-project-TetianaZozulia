<?php

declare(strict_types=1);

namespace App\Module\Subscription\Service\BusinessCase;

use App\Module\Subscription\Exception\DuplicateEmailException;
use App\Module\Subscription\Exception\LockBusyException;
use App\Model\Email;
use App\Module\Subscription\Model\ResourceModel\SubscriberResource;
use App\Module\Subscription\Model\Subscriber;
use App\Model\Topic;
use App\Module\Subscription\Service\Subscription\SubscriptionInterface;

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
        $subscriber = new Subscriber(
            new Email($resource->getEmail()),
            new Topic($resource->getTopic())
        );
        $this->subscription->addSubscriber($subscriber);
    }
}
