<?php

declare(strict_types=1);

namespace App\Logger;

use App\Enum\ErrorType;
use App\Serializer\JsonSerializer;
use Psr\Log\LoggerInterface;

class CustomErrorLogger implements LoggerInterface
{
    public function __construct(
        private LogPublisherInterface $logPublisher,
        private LogPublisherInterface $logPublisher2,
        private JsonSerializer $serializer
    ) {
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::Error);
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::Error);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::Error);
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::Error);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::Error);
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::Error);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::Error);
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::Error);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::Error);
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::Error);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::Info);
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::Info);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::Info);
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::Info);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::Debug);
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::Debug);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->logPublisher->publish($message . $this->serializeContext($context),ErrorType::from($level));
        $this->logPublisher2->publish($message . $this->serializeContext($context),ErrorType::from($level));
    }

    private function serializeContext(array $context): string
    {
        if (count($context) === 0) {
            return '';
        }
        $resultContext = $context;
        foreach ($context as $key => $value) {
            $resultContext["{{$key}}"] = match ($value) {
                null === $value, \is_scalar($value), $value instanceof \Stringable => $value,
                $value instanceof \DateTimeInterface => $value->format(\DateTimeInterface::RFC3339),
                \is_object($value) => '[object ' . $value::class . ']',
                default => '[' . \gettype($value) . ']',
            };
        }
        return $this->serializer->serialize($resultContext);
    }
}
