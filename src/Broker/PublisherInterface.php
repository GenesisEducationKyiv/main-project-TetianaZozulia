<?php

declare(strict_types=1);

namespace App\Broker;

use App\Enum\ErrorType;

interface PublisherInterface
{
    public function publish(string $message, ErrorType $errorType): void;
}
