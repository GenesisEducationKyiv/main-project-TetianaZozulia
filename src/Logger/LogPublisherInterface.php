<?php

declare(strict_types=1);

namespace App\Logger;

use App\Enum\ErrorType;

interface LogPublisherInterface
{
    public function publish(string $message, ErrorType $errorType): void;
}
