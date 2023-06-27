<?php declare(strict_types=1);

namespace App\Service\Subscription;

use App\Model\SubscriberModel;
use App\Repository\SubscribersRepository;
use App\Serializer\JsonSerializer;

class Subscription implements SubscriptionInterface
{
    public function __construct(
        private SubscribersRepository $subscribersRepository,
        private JsonSerializer $serializer,
    ) {
    }

    public function addSubscriber(SubscriberModel $subscriber): void
    {
        $subscribersList = $this->subscribersRepository->existFile($subscriber->getTopic())
            ? $this->subscribersRepository->read($subscriber->getTopic())
            : '';
        $emails = $this->serializer->deserialize($subscribersList) ?? [];
        array_push($emails, $subscriber->getEmail()->getEmail());
        $emails = array_unique($emails);
        $this->subscribersRepository->write(
            $subscriber->getTopic(),
            $this->serializer->serialize($emails)
        );
    }
}
