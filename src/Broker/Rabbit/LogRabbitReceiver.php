<?php

declare(strict_types=1);

namespace App\Broker\Rabbit;

use App\Enum\ErrorType;
use App\Logger\LogReceiverInterface;

class LogRabbitReceiver implements LogReceiverInterface
{
    public function __construct(
        private RabbitClient $client,
        private string $exchangeName,
        private string $exchangeType
    ) {

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
