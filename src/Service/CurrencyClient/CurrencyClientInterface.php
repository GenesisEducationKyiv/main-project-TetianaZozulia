<?php declare(strict_types=1);

namespace App\Service\CurrencyClient;

use App\Model\Rate;

interface CurrencyClientInterface
{
    public function getRate(string $fromCurrency = 'USD', string $toCurrency = 'BTC'): Rate;
}
