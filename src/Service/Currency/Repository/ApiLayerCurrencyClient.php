<?php

declare(strict_types=1);

namespace App\Service\Currency\Repository;

use App\Exception\CurrencyApiFailedException;
use App\Map\ApiLayerRate;
use App\Model\RateInterface;
use App\Model\ResourceModel\CurrencyResourceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiLayerCurrencyClient implements CurrencyClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private ApiLayerRate $apiRateMapper,
        private string $apiHost,
        private string $apiKey
    ) {
    }

    public function getRate(CurrencyResourceInterface $currencyResource): RateInterface
    {
        try {
            $path = '/currency_data/convert';
            $response = $this->httpClient->request('GET', $this->apiHost . $path, [
                    'query' => [
                        'apikey' => $this->apiKey,
                        'from' => $currencyResource->getFrom()->toString(),
                        'to' => $currencyResource->getTo()->toString(),
                        'amount' => 1,
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode > Response::HTTP_OK) {
                throw new CurrencyApiFailedException(__CLASS__, $statusCode);
            }
            $this->logger->info('ApiLayerCurrencyClient return result: ', $response->toArray());
            return $this->apiRateMapper->fromArray($response->toArray());
        } catch (\Exception $exception) {
            throw new CurrencyApiFailedException(__CLASS__, Response::HTTP_BAD_REQUEST);
        }
    }
}
