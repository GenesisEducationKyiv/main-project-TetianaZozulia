<?php

declare(strict_types=1);

namespace App\Broker;

interface ReceiverInterface
{
    public function receive(array $routeKeys): void;
}
