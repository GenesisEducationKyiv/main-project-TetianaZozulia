<?php

declare(strict_types=1);

namespace App\Module\Rate\Map;

use App\Map\MapperInterface;
use App\Module\Rate\Model\Rate;
use App\Module\Rate\Model\RateInterface;
use App\Module\Rate\Type\CurrencyName;

class ApiRate implements MapperInterface
{
    public function toArray($object): array
    {
        if (! $object instanceof RateInterface) {
            throw new \InvalidArgumentException('Argument have to be instance of RateInterface');
        }

        return [
            'target' => $object->getFromCurrencyName()->toString(),
            'currencyTo' => $object->getToCurrencyName()->toString(),
            'rates' => [$object->getToCurrencyName()->toString() => $object->getRate()],
            'timestamp' => $object->getUpdateAt(),
        ];
    }

    public function fromArray(array $ar): RateInterface
    {
        return new Rate(
            new CurrencyName($ar['target']),
            new CurrencyName($ar['currencyTo']),
            $ar['rates'][$ar['currencyTo']],
            $ar['timestamp']
        );
    }
}
