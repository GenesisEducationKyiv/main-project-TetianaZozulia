<?php

declare(strict_types=1);

namespace App\Tests\Functional\Module\Rate\Controller;

use App\Tests\Functional\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetRateCurrencyControllerTest extends AbstractWebTestCase
{
    public function testExpects200(): void
    {
        self::httpGet('/api/rate', ['from' => 'btc', 'to' => 'usd']);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testExpectsNotEmptyData(): void
    {
        $response = self::httpGet('/api/rate', ['from' => 'btc', 'to' => 'usd']);
        self::assertJson($response);
        $responseData = json_decode($response, true);
        self::assertArrayHasKey('data', $responseData);
        self::assertArrayHasKey('rate', $responseData['data']);
    }
}
