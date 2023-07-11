<?php

declare(strict_types=1);

namespace App\Module\Rate\Map;

use App\Map\MapperInterface;
use App\Module\Rate\Model\Rate;
use App\Module\Rate\Model\RateInterface;
use App\Module\Rate\Type\CurrencyName;

class ApiLayerRate implements MapperInterface
{
    public function toArray($object): array
    {
        if (! $object instanceof RateInterface) {
            throw new \InvalidArgumentException('Argument have to be instance of RateInterface');
        }

        return [];
    }

    public function fromArray(array $ar)
    {
        return new Rate(
            new CurrencyName($ar['query']['from']),
            new CurrencyName($ar['query']['to']),
            $ar['result'],
            $ar['info']['timestamp']
        );
    }
}
