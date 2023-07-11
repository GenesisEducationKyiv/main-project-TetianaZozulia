<?php

declare(strict_types=1);

namespace App\Module\Rate\Map;

use App\Map\MapperInterface;
use App\Module\Rate\Model\ResourceModel\CurrencyResource as CurrencyResourceModel;
use App\Module\Rate\Model\ResourceModel\CurrencyResourceInterface;
use App\Module\Rate\Type\CurrencyName;

class CurrencyResource implements MapperInterface
{
    public function toArray($object): array
    {
        if (! $object instanceof CurrencyResourceInterface) {
            throw new \InvalidArgumentException('Argument have to be instance of RateInterface');
        }

        return [
            'from' => $object->getFrom()->toString(),
            'to' => $object->getTo()->toString(),
        ];
    }

    public function fromArray(array $ar): CurrencyResourceModel
    {
        return new CurrencyResourceModel(
            new CurrencyName($ar['from']),
            new CurrencyName($ar['to']),
        );
    }
}
