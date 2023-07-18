<?php

declare(strict_types=1);

namespace App\Broker\Rabbit;

use App\Enum\ErrorType;
use App\Logger\LogPublisherInterface;

class LogRabbitPublisher implements LogPublisherInterface
{
    public function __construct(
        private RabbitClient $rabbitClient,
        private string $exchangeName,
        private string $exchangeType
    ) {
    }

    public function publish(string $message, ErrorType $errorType): void
    {
        $this->rabbitClient->createExchange($this->exchangeName, $this->exchangeType);
        $this->rabbitClient->publishToExchange($this->exchangeName, $errorType->value, $message);
//        $this->rabbitClient->reset();
    }
}
