<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Subscription;

use App\Model\Email;
use App\Model\SubscriberModel;
use App\Model\Topic;
use App\Service\Storage\FileService;
use App\Service\Subscription\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testExpectsAddSubscriberToStorage(): void
    {
        $testFileService = new FileService(
            './tests/data/'
        );
        $testFileService->delete('currency_subscribers.json');

        $subscription = new Subscription(
            $testFileService,
            'subscribers/'
        );

        $subscription->addSubscriber(new SubscriberModel(
            new Email('test.test@gmail.com'),
            new Topic('currency')
        ));

        $jsonResult = $testFileService->read('subscribers/currency_subscribers.json');
        $actualSubscribers = json_decode($jsonResult, true);
        self::assertArrayHasKey('test.test@gmail.com', array_flip($actualSubscribers));
    }
}
