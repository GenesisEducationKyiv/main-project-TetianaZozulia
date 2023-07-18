<?php

declare(strict_types=1);

namespace App\Enum;

enum RabbitExchangeType : string {
    case Direct = 'direct';
    case Fanout = 'fanout';
}
