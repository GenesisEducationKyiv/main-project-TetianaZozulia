<?php

declare(strict_types=1);

namespace App\Module\Rate\Service\Currency\Client;

use App\Module\Rate\Enum\ApiName;
use App\Module\Rate\Exception\CurrencyApiFailedException;
use App\Module\Rate\Map\CoinGecoApiRate;
use App\Module\Rate\Model\RateInterface;
use App\Module\Rate\Model\ResourceModel\CurrencyResourceInterface;
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
