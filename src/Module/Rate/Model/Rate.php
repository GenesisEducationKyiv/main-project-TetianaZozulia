<?php

declare(strict_types=1);

namespace App\Module\Rate\Model;

use App\Module\Rate\Type\CurrencyName;

class Rate implements RateInterface
{
    public function __construct(
        private CurrencyName $fromCurrencyName,
        private CurrencyName $toCurrencyName,
        private float $rate,
        private int $updateAt
    ) {
    }

    public function getFromCurrencyName(): CurrencyName
    {
        return $this->fromCurrencyName;
    }

    public function getToCurrencyName(): CurrencyName
    {
        return $this->toCurrencyName;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getUpdateAt(): int
    {
        return $this->updateAt;
    }
}
