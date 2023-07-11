<?php

declare(strict_types=1);

namespace App\Module\Rate\Map;

use App\Map\MapperInterface;
use App\Module\Rate\Model\RateInterface;
use App\Module\Rate\Model\Rate as RateModel;
use App\Module\Rate\Type\CurrencyName;

class Rate implements MapperInterface
{
    public function toArray($object): array
    {
        if (! $object instanceof RateInterface) {
            throw new \InvalidArgumentException('Argument have to be instance of RateInterface');
        }

        return [
           'fromCurrencyName' => $object->getFromCurrencyName()->toString(),
           'toCurrencyName' => $object->getToCurrencyName()->toString(),
           'rate' => $object->getRate(),
           'updateAt' => $object->getUpdateAt(),
        ];
    }

    public function fromArray(array $ar): RateInterface
    {
        return new RateModel(
            new CurrencyName($ar['fromCurrencyName']),
            new CurrencyName($ar['toCurrencyName']),
            $ar['rate'],
            $ar['updateAt']
        );
    }
}
