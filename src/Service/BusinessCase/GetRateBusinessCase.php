<?php

declare(strict_types=1);

namespace App\Service\BusinessCase;

use App\Model\Rate;
use App\Model\ResourceModel\CurrencyResource;
use App\Model\ResourceModel\CurrencyResourceInterface;
use App\Repository\RateRepositoryInterface;
use App\Service\Currency\Handler\AbstractCurrencyHandler;
use App\Type\CurrencyName;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class GetRateBusinessCase
{
    private CurrencyResource $defaultCurrency;

    public function __construct(
        private RateRepositoryInterface $rateRepository,
        private AbstractCurrencyHandler $currencyClient,
        private string $defaultCurrencyFrom,
        private string $defaultCurrencyTo
    ) {
        $this->defaultCurrency = new CurrencyResource(
            new CurrencyName($this->defaultCurrencyFrom),
            new CurrencyName($this->defaultCurrencyTo),
        );
    }

    public function execute(?CurrencyResourceInterface $currencyResource = null): Rate
    {
        try {
            $rate = $this->rateRepository->read($currencyResource ?? $this->defaultCurrency);
        } catch (FileNotFoundException $exception) {
            $rate = $this->currencyClient->getCurrencyRate($currencyResource ?? $this->defaultCurrency);
            $this->rateRepository->write($rate);
        }
        return $rate;
    }
}
