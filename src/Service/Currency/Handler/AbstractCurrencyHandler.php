<?php

declare(strict_types=1);

namespace App\Service\Currency\Handler;

use App\Exception\CurrencyApiFailedException;
use App\Model\RateInterface;
use App\Model\ResourceModel\CurrencyResourceInterface;
use App\Service\Currency\Repository\CurrencyClientInterface;

abstract class AbstractCurrencyHandler
{
    protected ?AbstractCurrencyHandler $next = null;

    public function __construct(
        protected CurrencyClientInterface $client,
    ) {
    }

    final public function processNext(CurrencyResourceInterface $currencyResource): ?RateInterface
    {
        if (!$this->next) {
            return null;
        }
       return  $this->next->getCurrencyRate($currencyResource);
    }

    public function setNext(AbstractCurrencyHandler $next): void
    {
        $this->next = $next;
    }

    public function getCurrencyRate(CurrencyResourceInterface $currencyResource): RateInterface
    {
        try {
            return $this->client->getRate($currencyResource);
        } catch (CurrencyApiFailedException $exception) {
            return $this->processNext($currencyResource);
        }
    }
}
