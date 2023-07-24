<?php

declare(strict_types=1);

namespace App\Module\Subscription\Service\Subscription;

use App\Model\Topic;
use App\Module\Subscription\Exception\DuplicateEmailException;
use App\Module\Subscription\Exception\LockBusyException;
use App\Module\Subscription\Model\Subscriber;
use App\Module\Subscription\Repository\SubscribersRepositoryInterface;
use App\Module\Subscription\Service\Lock\LockServiceInterface;

class Subscription implements SubscriptionInterface
{
    public function __construct(
        private SubscribersRepositoryInterface $subscribersRepository,
        private LockServiceInterface $lockService
    ) {
    }

    /**
     * @throws DuplicateEmailException
     * @throws LockBusyException
     */
    public function addSubscriber(Subscriber $subscriber): void
    {
        $lockName = trim($subscriber->getTopic()->getFileName(), '/');
        if (! $this->lockService->getLock($lockName)) {
            throw new LockBusyException($lockName);
        }

        $subscribersList = $this->subscribersRepository->existFile(
            $subscriber->getTopic()
        ) ? $this->subscribersRepository->read($subscriber->getTopic()) : [];

        if (array_key_exists($subscriber->getEmail()->toString(), array_flip($subscribersList))) {
            $this->lockService->releaseLock($lockName);
            throw new DuplicateEmailException($subscriber->getEmail()->toString());
        }
        $subscribersList[] = $subscriber->getEmail()->toString();
        $this->subscribersRepository->write(
            $subscriber->getTopic(),
            array_unique($subscribersList)
        );
        $this->lockService->releaseLock($lockName);
    }

    public function getSubscribers(Topic $topic): array
    {
       return $this->subscribersRepository->existFile($topic)
           ? $this->subscribersRepository->read($topic)
           : [];
    }
}
