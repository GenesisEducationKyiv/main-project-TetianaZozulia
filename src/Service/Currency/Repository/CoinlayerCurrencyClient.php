<?php

declare(strict_types=1);

namespace App\Service\Currency\Repository;

use App\Event\ApiReturnResponse;
use App\Exception\CurrencyApiFailedException;
use App\Map\ApiRate;
use App\Model\RateInterface;
use App\Model\ResourceModel\CurrencyResourceInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinlayerCurrencyClient implements CurrencyClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private EventDispatcherInterface $eventDispatcher,
        private ApiRate $apiRateMapper,
        private string $apiHost,
        private string $apiKey
    ) {
    }

    /**
     * @throws CurrencyApiFailedException
     */
    public function getRate(CurrencyResourceInterface $currencyResource): RateInterface
    {
        try {
            $path = '/live';
            $response = $this->httpClient->request(
                'GET',
                $this->apiHost . $path,
                [
                    'query' => [
                        'access_key' => $this->apiKey,
                        'from' => $currencyResource->getFrom()->toString(),
                        'to' => $currencyResource->getTo()->toString(),
                        'amount' => 1,
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]
            );

            $statusCode = $response->getStatusCode();
            if ($statusCode > Response::HTTP_OK) {
                throw new CurrencyApiFailedException(__CLASS__, $statusCode);
            }

            $rate = array_merge($response->toArray(), ['currencyTo' => $currencyResource->getTo()->toString()]);
            $rate = $this->apiRateMapper->fromArray($rate);

            $event = new ApiReturnResponse($rate, __CLASS__);
            $this->eventDispatcher->dispatch(
                $event,
                $event::NAME

            );

            return $rate;
        } catch (\Exception $exception) {
            throw new CurrencyApiFailedException(__CLASS__, Response::HTTP_BAD_REQUEST);
        }
    }
}
