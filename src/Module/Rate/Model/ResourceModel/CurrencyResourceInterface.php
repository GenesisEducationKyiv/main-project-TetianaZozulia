<?php

declare(strict_types=1);

namespace App\Module\Rate\Model\ResourceModel;

use App\Module\Rate\Type\CurrencyName;

interface CurrencyResourceInterface
{
    public function getFrom(): CurrencyName;

    public function getTo(): CurrencyName;
}
