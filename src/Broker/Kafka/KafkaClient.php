<?php

declare(strict_types=1);

namespace App\Broker\Kafka;

use App\Enum\ErrorType;
use \RdKafka\Conf;
use \RdKafka\Producer;
use \RdKafka\TopicConf;
use \RdKafka\Message;
use \RdKafka\Consumer;

class KafkaClient
{
    public function createConf(array $settings): Conf
    {
        $conf = new Conf();
        foreach ($settings as $setting) {
            foreach ($setting as $key => $value) {
                $conf->set($key, $value);
            }
        }
        return $conf;
    }

    public function produce(Conf $conf, string $topicName, string $message): void
    {
        $producer = new Producer($conf);

        $topic = $producer->newTopic($topicName);

        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $producer->poll(0);
        $producer->flush(10000);
    }

    public function createTopicConfig(array $settings): TopicConf
    {
        $topicConf = new TopicConf();
        foreach ($settings as $setting) {
            foreach ($setting as $key => $value) {
                $topicConf->set($key, $value);
            }
        }
        return $topicConf;
    }

    public function consume(Conf $conf, string $brokerName, string $topicName, TopicConf $topicConf)
    {
        if (empty($topicName)) {
            throw new \InvalidArgumentException('Empty kafka topic name');
        }

        if (!ErrorType::tryFrom($topicName)) {
            throw new \InvalidArgumentException(sprintf('Undefined kafka topic name "%s"', $topicName));
        }

        $consumer = new Consumer($conf);
        $consumer->addBrokers($brokerName);

        $topic = $consumer->newTopic($topicName, $topicConf);

        // Start consuming partition 0
        $topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);

        while (true) {
            $message = $topic->consume(0, 120*10000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $this->onMessage($message);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timed out\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
            }
        }
    }

    private function onMessage(Message $msg)
    {
        echo ' [x] ', $msg->topic_name, ':', $msg->payload, "\n";
    }
}
