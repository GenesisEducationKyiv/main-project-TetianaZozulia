<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Rate as RateModel;
use App\Model\ResourceModel\CurrencyResourceInterface;

interface RateRepositoryInterface
{
    public function read(?CurrencyResourceInterface $currencyResource): RateModel;

    public function write(RateModel $rate): void;
}
