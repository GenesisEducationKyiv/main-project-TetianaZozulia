<?php

declare(strict_types=1);

namespace App\Service\Currency\Handler;

use App\Enum\ApiName;

class CoinlayerCurrencyHandler extends AbstractCurrencyHandler
{
    protected const API_NAME = ApiName::CoinLayer;
}
