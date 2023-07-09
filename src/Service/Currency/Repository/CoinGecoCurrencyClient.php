<?php

declare(strict_types=1);

namespace App\Service\Currency\Repository;

use App\Enum\ApiName;
use App\Exception\CurrencyApiFailedException;
use App\Map\CoinGecoApiRate;
use App\Model\RateInterface;
use App\Model\ResourceModel\CurrencyResourceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinGecoCurrencyClient implements CurrencyClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private CoinGecoApiRate $apiRateMapper,
        private string $apiHost
    ) {
    }

    /**
     * @throws CurrencyApiFailedException
     */
    public function getRate(CurrencyResourceInterface $currencyResource): RateInterface
    {
        try {
            $path = '/api/v3/simple/price';
            $response = $this->httpClient->request(
                'GET',
                $this->apiHost . $path,
                [
                    'query' => [
                        'ids' => $currencyResource->getFrom()->toString(),
                        'vs_currencies' => $currencyResource->getTo()->toString()
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]
            );

            $statusCode = $response->getStatusCode();
            if ($statusCode > Response::HTTP_OK) {
                throw new CurrencyApiFailedException(
                    'Exception in ' . ApiName::CoinGeco->value . '. ',
                    $statusCode
                );
            }
            $rate = array_merge($response->toArray(), [
                'currencyTo' => strtolower($currencyResource->getTo()->toString()),
                'currencyFrom' => strtolower($currencyResource->getFrom()->toString()),
            ]);

            return $this->apiRateMapper->fromArray($rate);
        } catch (\Exception $exception) {
            throw new CurrencyApiFailedException(
                'Exception in ' . ApiName::CoinGeco->value . '. ' . $exception->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
