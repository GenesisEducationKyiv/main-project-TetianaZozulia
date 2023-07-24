<?php
declare(strict_types=1);

namespace App\Tests\Unit\Module\Mailer\Service\Processing;

use App\Enum\Topic;
use App\Model\Topic as TopicModel;
use App\Module\Mailer\Service\Processing\Processing;
use App\Serializer\JsonSerializer;
use App\Storage\FileStorage\FileStorage;
use PHPUnit\Framework\TestCase;

class ProcessingTest extends TestCase
{
    public function testExpectsProcessingFileCreate(): void
    {
        $fileService = new FileStorage('./tests/data/');
        $topic = new TopicModel(Topic::Currency->value);
        $subscribersList = ['test1.test@gmail.com', 'test2.test@gmail.com'];

        $processingService = new Processing(
            $fileService,
            new JsonSerializer(),
            '/subscribers/processing/'
        );

        $processingService->createProcessingFile($topic, $subscribersList);
        self::assertFileExists('./tests/data/subscribers/processing/currency_subscribers.json');
        $actualSubscriberList = $processingService->getProcessingData($topic);
        self::assertEquals($subscribersList, $actualSubscriberList);
    }

    public function testExpectsProcessingFileDelete(): void
    {
        $fileService = new FileStorage('./tests/data/');
        $topic = new TopicModel(Topic::Currency->value);

        $processingService = new Processing(
            $fileService,
            new JsonSerializer(),
            '/subscribers/processing/'
        );

        $processingService->deleteProcessingFile($topic);
        self::assertFileDoesNotExist('./tests/data/subscribers/processing/currency_subscribers.json');
    }
}
