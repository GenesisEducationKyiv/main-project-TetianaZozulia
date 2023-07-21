<?php

declare(strict_types=1);

namespace App\Broker\Kafka;

use App\Logger\LogReceiverInterface;

class LogKafkaReceiver implements LogReceiverInterface
{
    public function __construct(
        private KafkaClient $client,
        private array $consumerConfig,
        private array $topicConfig,
        private string $brokerName
    ) {
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
