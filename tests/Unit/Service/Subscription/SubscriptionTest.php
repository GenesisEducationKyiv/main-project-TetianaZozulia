<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Subscription;

use App\Model\Email;
use App\Model\SubscriberModel;
use App\Model\Topic;
use App\Repository\SubscribersRepository;
use App\Serializer\JsonSerializer;
use App\Service\Storage\FileService;
use App\Service\Subscription\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testExpectsAddSubscriberToStorage(): void
    {
        $topic = new Topic('currency');
        $testFileService = new FileService(
            './tests/data/'
        );
        $testSubscriberRepo = new SubscribersRepository(
            $testFileService,
            'subscribers/'
        );
        $testSubscriberRepo->deleteAll($topic);

        $subscription = new Subscription(
            $testSubscriberRepo,
            new JsonSerializer()
        );

        $subscription->addSubscriber(new SubscriberModel(
            new Email('test.test@gmail.com'),
            $topic
        ));

        $jsonResult = $testSubscriberRepo->read($topic);
        $actualSubscribers = json_decode($jsonResult, true);
        self::assertArrayHasKey('test.test@gmail.com', array_flip($actualSubscribers));
    }
}
