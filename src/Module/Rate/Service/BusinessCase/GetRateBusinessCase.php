<?php

declare(strict_types=1);

namespace App\Module\Rate\Service\BusinessCase;

use App\Module\Rate\Model\Rate;
use App\Module\Rate\Model\ResourceModel\CurrencyResourceInterface;
use App\Module\Rate\Repository\RateRepositoryInterface;
use App\Module\Rate\Service\Currency\Handler\AbstractCurrencyHandler;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class GetRateBusinessCase
{

    public function __construct(
        private RateRepositoryInterface $rateRepository,
        private AbstractCurrencyHandler $currencyClient,
        private CurrencyResourceInterface $defaultCurrency
    ) {
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
