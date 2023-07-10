<?php

declare(strict_types=1);

namespace App\Service\Currency\Repository;

use App\Enum\ApiName;
use App\Exception\CurrencyApiFailedException;
use App\Map\ApiRate;
use App\Model\RateInterface;
use App\Model\ResourceModel\CurrencyResourceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinlayerCurrencyClient implements CurrencyClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
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
                throw new CurrencyApiFailedException(
                    'Exception in ' . ApiName::CoinLayer->value . '. ',
                    $statusCode
                );
            }

            $rate = array_merge($response->toArray(), ['currencyTo' => $currencyResource->getTo()->toString()]);
            return $this->apiRateMapper->fromArray($rate);
        } catch (\Exception $exception) {
            throw new CurrencyApiFailedException(
                'Exception in ' . ApiName::CoinLayer->value . '. ' . $exception->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
