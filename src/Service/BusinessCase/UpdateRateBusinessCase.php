<?php declare(strict_types=1);

namespace App\Service\BusinessCase;

use App\Repository\RateRepositoryInterface;
use App\Service\CurrencyClient\CurrencyClientInterface;

class UpdateRateBusinessCase
{
    public function __construct(
        private CurrencyClientInterface $currencyClient,
        private RateRepositoryInterface $rateRepository
    ) {
    }

    public function execute(): void
    {
        $rate = $this->currencyClient->getRate();
        $this->rateRepository->write($rate);
    }
}
