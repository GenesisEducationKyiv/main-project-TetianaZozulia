<?php declare(strict_types=1);

namespace App\Service\Subscription;

use App\Model\SubscriberModel;
use App\Repository\SubscribersRepository;

class Subscription implements SubscriptionInterface
{
    public function __construct(
        private SubscribersRepository $subscribersRepository
    ) {
    }

    public function addSubscriber(SubscriberModel $subscriber): void
    {
        $subscribersList = $this->subscribersRepository->existFile($subscriber->getTopic())
            ? $this->subscribersRepository->read($subscriber->getTopic())
            : [];
        array_push($subscribersList, $subscriber->getEmail()->getEmail());
        $this->subscribersRepository->write(
            $subscriber->getTopic(),
            array_unique($subscribersList)
        );
    }
}
