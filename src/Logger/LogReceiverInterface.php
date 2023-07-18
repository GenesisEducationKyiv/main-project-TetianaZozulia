<?php

declare(strict_types=1);

namespace App\Logger;

interface LogReceiverInterface
{
    public function receive(array $routeKeys): void;
}
