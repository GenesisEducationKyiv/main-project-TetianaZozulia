<?php

declare(strict_types=1);

namespace App\Module\Rate\Service\Currency\Handler;

use App\Module\Rate\Enum\ApiName;

class CoinGecoCurrencyHandler extends AbstractCurrencyHandler
{
    protected const API_NAME = ApiName::CoinGeco;
}
