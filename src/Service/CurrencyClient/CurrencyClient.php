<?php declare(strict_types=1);

namespace App\Service\CurrencyClient;

use App\Map\ApiRate;
use App\Model\Rate;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyClient implements CurrencyClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private ApiRate $apiRateMapper,
        private string $apiHost,
        private string $apiKey
    ) {
    }

    public function getRate(string $fromCurrency = 'USD', string $toCurrency = 'BTC'): Rate
    {
        $path = '/live';
        $response = $this->httpClient->request(
            'GET',
            $this->apiHost . $path,
            [
                'query' => [
                    'access_key' => $this->apiKey,
                    'from' => $fromCurrency,
                    'to' => $toCurrency,
                    'amount' => 1,
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode >= 300 && $statusCode <= 599) {
            throw new \HttpException('Api execute failed.', $statusCode);
        }

        $data = array_merge($response->toArray(), ['currencyTo' => $toCurrency]);
        return $this->apiRateMapper->fromArray($data);
    }
}
