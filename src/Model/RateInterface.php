<?php declare(strict_types=1);

namespace App\Model;

use App\Type\CurrencyName;

interface RateInterface
{
    /**
     * @return CurrencyName
     */
    public function getFromCurrencyName(): CurrencyName;

    /**
     * @return CurrencyName
     */
    public function getToCurrencyName(): CurrencyName;

    /**
     * @return float
     */
    public function getRate(): float;

    /**
     * @return int
     */
    public function getUpdateAt(): int;
}