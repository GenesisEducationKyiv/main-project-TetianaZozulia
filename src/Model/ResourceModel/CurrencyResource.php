<?php

declare(strict_types=1);

namespace App\Model\ResourceModel;

use App\Type\CurrencyName;

class CurrencyResource implements ResourceInterface, CurrencyResourceInterface
{
    public function __construct(
        private CurrencyName $from,
        private CurrencyName $to,
    ) {
    }

    /**
     * @return CurrencyName
     */
    public function getFrom(): CurrencyName
    {
        return $this->from;
    }

    /**
     * @return CurrencyName
     */
    public function getTo(): CurrencyName
    {
        return $this->to;
    }
}
