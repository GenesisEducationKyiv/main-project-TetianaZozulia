<?php
declare(strict_types=1);

namespace App\Broker\Kafka;

use App\Enum\ErrorType;
use App\Logger\LogPublisherInterface;

class LogKafkaPublisher implements LogPublisherInterface
{
    public function __construct(private KafkaClient $kafkaClient)
    {
    }

    public function publish(string $message, ErrorType $errorType): void
    {
        // TODO: Implement publish() method.
    }
}
