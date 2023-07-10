<?php

declare(strict_types=1);

namespace App\Service\Currency\Repository;

use App\Exception\CurrencyApiFailedException;
use App\Model\RateInterface;
use App\Model\ResourceModel\CurrencyResourceInterface;

interface CurrencyClientInterface
{
    /**
     * @throws CurrencyApiFailedException
     */
    public function getRate(CurrencyResourceInterface $currencyResource): RateInterface;
}
