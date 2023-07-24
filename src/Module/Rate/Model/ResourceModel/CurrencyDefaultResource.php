<?php

declare(strict_types=1);

namespace App\Module\Rate\Model\ResourceModel;

use App\Model\ResourceModel\ResourceInterface;
use App\Module\Rate\Type\CurrencyName;

class CurrencyDefaultResource implements ResourceInterface, CurrencyResourceInterface
{
    public function __construct(
        private string $from,
        private string $to,
    ) {
    }

    public function getFrom(): CurrencyName
    {
        return new CurrencyName($this->from);
    }

    public function getTo(): CurrencyName
    {
        return new CurrencyName($this->to);
    }
}
