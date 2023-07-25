<?php
declare(strict_types=1);

namespace App\Broker\Rabbit;

use App\Broker\PublisherInterface;
use App\Broker\ReceiverInterface;
use App\Enum\ErrorType;

class RabbitBroker implements PublisherInterface, ReceiverInterface
{
    public const BROKER_NAME = 'rabbit';

    public function __construct(
        private RabbitClient $client,
        private string $exchangeName,
        private string $exchangeType
    ) {
    }

    public function publish(string $message, ErrorType $errorType): void
    {
        $this->client->createExchange($this->exchangeName, $this->exchangeType);
        $this->client->publishToExchange($this->exchangeName, $errorType->value, $message);
//        $this->rabbitClient->reset();
    }

    public function receive(array $routeKeys): void
    {
        $this->client->createExchange($this->exchangeName, $this->exchangeType);
        $queue_name = $this->client->prepareAndBinding(
            $this->exchangeName,
            $routeKeys
        );
        $this->client->consume($queue_name);
        $this->client->wait();
//        $channel->reset();
    }
}
