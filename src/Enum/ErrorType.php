<?php

declare(strict_types=1);

namespace App\Enum;

enum ErrorType: string {
    case Error = 'error';
    case Info = 'info';
    case Debug = 'debug';
}
