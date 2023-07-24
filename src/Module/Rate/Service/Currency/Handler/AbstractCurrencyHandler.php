<?php

declare(strict_types=1);

namespace App\Module\Rate\Service\Currency\Handler;

use App\Module\Rate\Enum\ApiName;
use App\Module\Rate\Event\ApiIncorrectResponse;
use App\Module\Rate\Event\ApiReturnResponse;
use App\Module\Rate\Exception\CurrencyApiFailedException;
use App\Module\Rate\Model\RateInterface;
use App\Module\Rate\Model\ResourceModel\CurrencyResourceInterface;
use App\Module\Rate\Service\Currency\Client\CurrencyClientInterface;
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
