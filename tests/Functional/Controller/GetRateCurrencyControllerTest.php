<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\AbstractWebTestCase;

class GetRateCurrencyControllerTest extends AbstractWebTestCase
{
    public function testExpects200(): void
    {
        self::httpGet('/api/rate');
        self::assertResponseStatusCodeSame(200);
    }

    public function testExpectsNotEmptyData(): void
    {
        $response = self::httpGet('/api/rate');
        self::assertJson($response);
        $responseData = json_decode($response, true);
        self::assertArrayHasKey('data', $responseData);
        self::assertArrayHasKey('rate', $responseData['data']);
        self::assertArrayHasKey('status', $responseData);
        self::assertEquals('succeed', $responseData['status']);
    }
}
