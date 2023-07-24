<?php

declare(strict_types=1);

namespace App\Module\Rate\Model;

use App\Module\Rate\Type\CurrencyName;

interface RateInterface
{
    public function getFromCurrencyName(): CurrencyName;

    public function getToCurrencyName(): CurrencyName;

    public function getRate(): float;

    public function getUpdateAt(): int;
}
