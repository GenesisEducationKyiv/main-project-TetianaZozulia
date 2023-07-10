<?php

declare(strict_types=1);

namespace App\Model\ResourceModel;

use App\Type\CurrencyName;

interface CurrencyResourceInterface
{
    public function getFrom(): CurrencyName;

    public function getTo(): CurrencyName;
}
