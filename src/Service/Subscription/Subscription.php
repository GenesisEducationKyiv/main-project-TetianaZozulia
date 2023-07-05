<?php

declare(strict_types=1);

namespace App\Service\Subscription;

use App\Exception\DuplicateEmailException;
use App\Exception\LockBusyException;
use App\Model\SubscriberModel;
use App\Repository\SubscribersRepository;
use App\Service\Lock\LockService;

class Subscription implements SubscriptionInterface
{
    private const LOCK_NAME = 'currency_subscribers.json';

    public function __construct(
        private SubscribersRepository $subscribersRepository,
        private LockService $lockService
    ) {
    }

    /**
     * @throws DuplicateEmailException
     * @throws LockBusyException
     */
    public function addSubscriber(SubscriberModel $subscriber): void
    {
        if (! $this->lockService->getLock(self::LOCK_NAME)) {
            throw new LockBusyException(self::LOCK_NAME);
        }

        $subscribersList = $this->subscribersRepository->existFile(
            $subscriber->getTopic()
        ) ? $this->subscribersRepository->read($subscriber->getTopic()) : [];

        if (array_key_exists($subscriber->getEmail()->toString(), array_flip($subscribersList))) {
            $this->lockService->releaseLock(self::LOCK_NAME);
            throw new DuplicateEmailException($subscriber->getEmail()->toString());
        }
        $subscribersList[] = $subscriber->getEmail()->toString();
        $this->subscribersRepository->write(
            $subscriber->getTopic(),
            array_unique($subscribersList)
        );
        $this->lockService->releaseLock(self::LOCK_NAME);
    }
}
