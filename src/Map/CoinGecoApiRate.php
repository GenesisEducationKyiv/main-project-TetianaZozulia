<?php

declare(strict_types=1);

namespace App\Map;

use App\Model\Rate;
use App\Model\RateInterface;
use App\Type\CurrencyName;

class CoinGecoApiRate implements MapperInterface
{
    public function toArray($object): array
    {
        if (! $object instanceof RateInterface) {
            throw new \InvalidArgumentException('Argument have to be instance of RateInterface');
        }

        return [];
    }

    public function fromArray(array $ar): RateInterface
    {
        return new Rate(
            new CurrencyName($ar['currencyFrom']),
            new CurrencyName($ar['currencyTo']),
            $ar[$ar['currencyFrom']][$ar['currencyTo']],
            time()
        );
    }
}
