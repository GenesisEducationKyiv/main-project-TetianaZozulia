<?php declare(strict_types=1);

namespace App\Model;

use App\Type\CurrencyName;

class Rate implements RateInterface
{
    public function __construct(
        private CurrencyName $fromCurrencyName,
        private CurrencyName $toCurrencyName,
        private float $rate,
        private int $updateAt
    ) {
    }

    /**
     * @return CurrencyName
     */
    public function getFromCurrencyName(): CurrencyName
    {
        return $this->fromCurrencyName;
    }

    /**
     * @return CurrencyName
     */
    public function getToCurrencyName(): CurrencyName
    {
        return $this->toCurrencyName;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @return int
     */
    public function getUpdateAt(): int
    {
        return $this->updateAt;
    }
}
