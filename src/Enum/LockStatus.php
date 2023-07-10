<?php
declare(strict_types=1);

namespace App\Enum;

enum LockStatus : int {
    case InUse = 1;
    case Free = 0;
}
