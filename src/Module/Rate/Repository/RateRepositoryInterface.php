<?php

declare(strict_types=1);

namespace App\Module\Rate\Repository;

use App\Module\Rate\Model\Rate as RateModel;
use App\Module\Rate\Model\ResourceModel\CurrencyResourceInterface;

interface RateRepositoryInterface
{
    public function read(?CurrencyResourceInterface $currencyResource): RateModel;

    public function write(RateModel $rate): void;
}
