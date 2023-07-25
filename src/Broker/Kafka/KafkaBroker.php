<?php
declare(strict_types=1);

namespace App\Broker\Kafka;

use App\Broker\PublisherInterface;
use App\Broker\ReceiverInterface;
use App\Enum\ErrorType;

class KafkaBroker implements PublisherInterface, ReceiverInterface
{
    public const BROKER_NAME = 'kafka';
    public function __construct(
        private KafkaClient $client,
        private array $publishConfig,
        private array $consumerConfig,
        private array $topicConfig,
        private string $brokerName
    ) {
    }

    public function publish(string $message, ErrorType $errorType): void
    {
        $config = $this->client->createConf($this->publishConfig);
        $this->client->produce($config, $errorType->value, $message);
    }

    public function receive(array $routeKeys): void
    {
        $conf = $this->client->createConf($this->consumerConfig);
        $topicConf = $this->client->createTopicConfig($this->topicConfig);
        foreach ($routeKeys as $topicName) {
            $this->client->consume($conf, $this->brokerName, $topicName, $topicConf);
        }
    }
}
