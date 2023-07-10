<?php

declare(strict_types=1);

namespace App\Service\BusinessCase;

use App\Model\ResourceModel\CurrencyResource;
use App\Model\ResourceModel\CurrencyResourceInterface;
use App\Repository\RateRepositoryInterface;
use App\Service\Currency\Handler\AbstractCurrencyHandler;
use App\Type\CurrencyName;

class UpdateRateBusinessCase
{
    private CurrencyResource $defaultCurrency;

    public function __construct(
        private AbstractCurrencyHandler $currencyClient,
        private RateRepositoryInterface $rateRepository,
        private string $defaultCurrencyFrom,
        private string $defaultCurrencyTo
    ) {
        $this->defaultCurrency = new CurrencyResource(
            new CurrencyName($this->defaultCurrencyFrom),
            new CurrencyName($this->defaultCurrencyTo),
        );
    }

    public function execute(?CurrencyResourceInterface $currencyResource = null): void
    {
        $rate = $this->currencyClient->getCurrencyRate($currencyResource ?? $this->defaultCurrency);
        $this->rateRepository->write($rate);
    }
}
