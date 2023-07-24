<?php

declare(strict_types=1);

namespace App\Module\Rate\Service\BusinessCase;

use App\Module\Rate\Model\ResourceModel\CurrencyResourceInterface;
use App\Module\Rate\Repository\RateRepositoryInterface;
use App\Module\Rate\Service\Currency\Handler\AbstractCurrencyHandler;

class UpdateRateBusinessCase
{

    public function __construct(
        private AbstractCurrencyHandler $currencyClient,
        private RateRepositoryInterface $rateRepository,
        private CurrencyResourceInterface $defaultCurrency
    ) {
    }

    public function execute(?CurrencyResourceInterface $currencyResource = null): void
    {
        $rate = $this->currencyClient->getCurrencyRate($currencyResource ?? $this->defaultCurrency);
        $this->rateRepository->write($rate);
    }
}
