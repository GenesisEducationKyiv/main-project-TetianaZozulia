<?php

declare(strict_types=1);

namespace App\Broker\Rabbit;

use App\Enum\ErrorType;
use App\Broker\Rabbit\Enum\RabbitExchangeType;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AbstractConnection;

class RabbitClient
{
    private ?AMQPChannel $channel = null;

    public function __construct(
        private AbstractConnection $connection,
        private bool $durable = false
    ) {
    }

    protected function prepare()
    {
        if ($this->channel === null) {
            $this->channel = $this->connection->channel();
        }
    }

    public function createExchange(string $name, string $type): void
    {
        if (! RabbitExchangeType::tryFrom($type)) {
            throw new \InvalidArgumentException(sprintf('Undefined rabbit exchange type "%s"', $type));
        }
        if (empty($name)) {
            throw new \InvalidArgumentException('Empty rabbit exchanger name');
        }
        $this->prepare();
        $this->channel->exchange_declare($name, $type, false, $this->durable, false);
    }

    public function publishToExchange(string $exchangeName, string $routingKey, string $body, array $meta = []): void
    {
        if (!ErrorType::tryFrom($routingKey)) {
            throw new \InvalidArgumentException(sprintf('Undefined rabbit route key "%s"', $routingKey));
        }
        $this->prepare();
        $this->channel->basic_publish(
            new AMQPMessage($body, $meta),
            $exchangeName,
            $routingKey
        );
    }

    public function prepareAndBinding(string $exchangeName, array $routeKeys): string
    {
        if (empty($routeKeys)) {
            throw new \InvalidArgumentException('Empty rabbit route keys');
        }

        foreach ($routeKeys as $key) {
            if (!ErrorType::tryFrom($key)) {
                throw new \InvalidArgumentException(sprintf('Undefined rabbit route key "%s"', $key));
            }
        }

        $this->prepare();
        list($queue_name, ,) = $this->channel->queue_declare(
            "",
            false,
            false,
            true,
            false
        );

        foreach ($routeKeys as $routeKey) {
            $this->channel->queue_bind($queue_name, $exchangeName, $routeKey);
        }
        return $queue_name;
    }

    public function consume(string $queue_name)
    {
        $this->prepare();
        $this->channel->basic_consume(
            $queue_name,
            '',
            false,
            true,
            false,
            false,
            [$this, 'onMessage']
        );

    }

    public function onMessage(AMQPMessage $msg)
    {
        echo ' [x] ', $msg->getRoutingKey(), ':', $msg->getBody(), "\n";
    }

    public function wait(): void
    {
        while ($this->channel !== null && $this->channel->is_open()) {
            $this->channel->wait();
        }
    }

    public function reset(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}
