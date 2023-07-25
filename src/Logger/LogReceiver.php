<?php
declare(strict_types=1);

namespace App\Logger;

use App\Broker\ReceiverInterface;

class LogReceiver implements ReceiverInterface
{
    public function __construct(
        private ReceiverInterface $receiver
    ) {
    }

    public function receive(array $routeKeys): void
    {
        $this->receiver->receive($routeKeys);
    }
}
