<?php

declare(strict_types=1);

namespace App\Service\Currency\Handler;

use App\Enum\ApiName;
use App\Event\ApiIncorrectResponse;
use App\Event\ApiReturnResponse;
use App\Exception\CurrencyApiFailedException;
use App\Model\RateInterface;
use App\Model\ResourceModel\CurrencyResourceInterface;
use App\Service\Currency\Repository\CurrencyClientInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AbstractCurrencyHandler
{
    protected const API_NAME = ApiName::CoinLayer;
    protected ?AbstractCurrencyHandler $next = null;

    public function __construct(
        protected CurrencyClientInterface $client,
        private EventDispatcherInterface $eventDispatcher
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
            $rate = $this->client->getRate($currencyResource);

            $event = new ApiReturnResponse($rate, static::API_NAME->value);
            $this->eventDispatcher->dispatch(
                $event,
                $event::NAME

            );

            return $rate;
        } catch (CurrencyApiFailedException $exception) {
            if (strpos($exception->getMessage(), 'Warning: Undefined array key')) {
                $event = new ApiIncorrectResponse($exception->getMessage(), static::API_NAME->value);
                $this->eventDispatcher->dispatch(
                    $event,
                    $event::NAME

                );
            }

            return $this->processNext($currencyResource);
        }
    }
}
