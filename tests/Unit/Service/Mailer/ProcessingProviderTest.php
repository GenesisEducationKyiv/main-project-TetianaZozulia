<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Mailer;

use App\Model\Topic;
use App\Repository\ProcessingSubscribersRepository;
use App\Repository\SubscribersRepository;
use App\Service\Storage\FileService;
use App\Service\SubscribersProcessingProvider\SubscribersProcessingProvider;
use PHPUnit\Framework\TestCase;

class ProcessingProviderTest extends TestCase
{
    public function testExpectsProcessingFileCreated(): void
    {
        $storageService = new FileService('./tests/data/');
        $processingSubscriberRepo = new ProcessingSubscribersRepository(
            $storageService,
            'subscribers/processing/'
        );
        $subscriberRepo = new SubscribersRepository(
            $storageService,
            'subscribers/'
        );
        $processingProvider = new SubscribersProcessingProvider(
            $processingSubscriberRepo,
            $subscriberRepo
        );
        $processingProvider->createProcessingFile(new Topic('currency'));

        $actualFile = $storageService->read('subscribers/processing/currency_subscribers.json');
        $expectedFile = $storageService->read('subscribers/currency_subscribers.json');
        self::assertEquals($expectedFile, $actualFile);
    }

    public function testExpectsProcessingFileDeleted(): void
    {
        $storageService = new FileService('./tests/data/');
        $processingSubscriberRepo = new ProcessingSubscribersRepository(
            $storageService,
            'subscribers/processing/'
        );
        $processingSubscriberRepo->write(new Topic('currency'), 'testes');

        $subscriberRepo = new SubscribersRepository(
            $storageService,
            'subscribers/'
        );

        $processingProvider = new SubscribersProcessingProvider(
            $processingSubscriberRepo,
            $subscriberRepo
        );

        $processingProvider->deleteProcessingFile(new Topic('currency'));

        self::assertFalse($processingSubscriberRepo->existFile(new Topic('currency')));
    }
}
