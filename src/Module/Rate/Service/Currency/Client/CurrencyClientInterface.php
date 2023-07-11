<?php

declare(strict_types=1);

namespace App\Module\Rate\Service\Currency\Client;

use App\Module\Rate\Exception\CurrencyApiFailedException;
use App\Module\Rate\Model\RateInterface;
use App\Module\Rate\Model\ResourceModel\CurrencyResourceInterface;

interface CurrencyClientInterface
{
    /**
     * @throws CurrencyApiFailedException
     */
    public function getRate(CurrencyResourceInterface $currencyResource): RateInterface;
}
