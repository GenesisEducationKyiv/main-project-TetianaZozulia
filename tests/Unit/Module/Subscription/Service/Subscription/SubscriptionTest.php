<?php declare(strict_types=1);

namespace App\Tests\Unit\Module\Subscription\Service\Subscription;

use App\Model\Email;
use App\Model\Topic as TopicModel;
use App\Enum\Topic;
use App\Module\Subscription\Exception\DuplicateEmailException;
use App\Module\Subscription\Exception\LockBusyException;
use App\Module\Subscription\Model\Subscriber;
use App\Module\Subscription\Repository\SubscribersRepository;
use App\Tests\Unit\Module\Subscription\Service\Lock\TestLockService;
use App\Module\Subscription\Service\Subscription\Subscription;
use App\Serializer\JsonSerializer;
use App\Storage\FileStorage\FileStorage;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function setUp(): void
    {
        $testFileService = new FileStorage('./tests/data/');
        $testFileService->delete('/subscribers/currency_subscribers.json');
        parent::setUp();
    }

    public function testExpectsAddSubscriberToStorage(): void
    {
        $subscription = $this->createSubscription(true, true);
        $subscription->addSubscriber(new Subscriber(
            new Email('test.test@gmail.com'),
            new TopicModel(Topic::Currency->value)
        ));
        self::assertFileExists('./tests/data/subscribers/currency_subscribers.json');
        $actualSubscribers = $subscription->getSubscribers(new TopicModel(Topic::Currency->value));
        self::assertArrayHasKey('test.test@gmail.com', array_flip($actualSubscribers));
    }

    public function testExpectsLockException(): void
    {
        $subscription = $this->createSubscription(false);
        self::expectException(LockBusyException::class);
        $subscription->addSubscriber(new Subscriber(
            new Email('test1.test@gmail.com'),
            new TopicModel(Topic::Currency->value)
        ));
    }

    public function testExpectsDuplicateEmailException(): void
    {
        $subscription = $this->createSubscription();
        $subscription->addSubscriber(new Subscriber(
            new Email('test.test@gmail.com'),
            new TopicModel(Topic::Currency->value)
        ));
        self::expectException(DuplicateEmailException::class);

        $subscription->addSubscriber(new Subscriber(
            new Email('test.test@gmail.com'),
            new TopicModel(Topic::Currency->value)
        ));
    }

    private function createSubscription(bool $isGetLock = true, bool $isDelete = false): Subscription
    {
        $topic = new TopicModel(Topic::Currency->value);
        $testFileService = new FileStorage('./tests/data/');
        $testLockService = new TestLockService($isGetLock);
        $testSubscriberRepo = new SubscribersRepository(
            $testFileService,
            new JsonSerializer(),
            'subscribers/'
        );
        if ($isDelete) {
            $testSubscriberRepo->delete($topic);
        }

        return new Subscription(
            $testSubscriberRepo,
            $testLockService
        );
    }
}
