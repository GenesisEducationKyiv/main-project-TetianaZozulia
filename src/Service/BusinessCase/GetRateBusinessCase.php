<?php declare(strict_types=1);

namespace App\Service\BusinessCase;

use App\Model\Rate;
use App\Repository\RateRepositoryInterface;
use App\Service\CurrencyClient\CurrencyClientInterface;

class GetRateBusinessCase
{
    public function __construct(
        private RateRepositoryInterface $rateRepository,
        private CurrencyClientInterface $currencyClient
    ) {
    }

    public function execute(): Rate
    {
        try {
            $rate = $this->rateRepository->read();
        } catch (\Throwable $exception) {
            $rate = $this->currencyClient->getRate();
            $this->rateRepository->write($rate);
        }
        return $rate;
    }
}
