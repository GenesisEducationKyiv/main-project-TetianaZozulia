<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\ApiReturnResponse;
use App\Map\Rate;
use App\Serializer\JsonSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApiResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private JsonSerializer $serializer,
        private Rate $rateMapper
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiReturnResponse::NAME => [
                ['logResponse', 0],
            ],
        ];
    }

    public function logResponse(ApiReturnResponse $event): void
    {
        $this->logger->info(
            sprintf('%s api client return result:', $event->getApiName())
            . $this->serializer->serialize($this->rateMapper->toArray($event->getRate()))
        );
    }
}
