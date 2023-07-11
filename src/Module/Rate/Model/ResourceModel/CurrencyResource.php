<?php

declare(strict_types=1);

namespace App\Module\Rate\Model\ResourceModel;

use App\Model\ResourceModel\ResourceInterface;
use App\Module\Rate\Type\CurrencyName;

class CurrencyResource implements ResourceInterface, CurrencyResourceInterface
{
    public function __construct(
        private CurrencyName $from,
        private CurrencyName $to,
    ) {
    }

    public function getFrom(): CurrencyName
    {
        return $this->from;
    }

    public function getTo(): CurrencyName
    {
        return $this->to;
    }
}
