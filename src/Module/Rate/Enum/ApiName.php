<?php

declare(strict_types=1);

namespace App\Module\Rate\Enum;

enum ApiName : string {
    case CoinLayer = 'CoinLayer';
    case CoinGeco = 'CoinGeco';
    case ApiLayer = 'ApiLayer';
}
