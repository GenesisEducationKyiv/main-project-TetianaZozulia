<?php
declare(strict_types=1);

namespace App\Logger;

use App\Broker\PublisherInterface;
use App\Enum\ErrorType;

class LogPublisher implements PublisherInterface
{
    public function __construct(
        private PublisherInterface $publisher
    ) {
    }

    public function publish(string $message, ErrorType $errorType): void
    {
        $this->publisher->publish($message, $errorType);
    }
}
