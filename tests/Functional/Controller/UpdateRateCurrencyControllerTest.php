<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\AbstractWebTestCase;

class UpdateRateCurrencyControllerTest extends AbstractWebTestCase
{
    public function testExpects200(): void
    {
        self::httpPatch('/api/rate/update', []);
        self::assertResponseStatusCodeSame(200);
    }

    public function testExpectsNotEmptyResponse(): void
    {
        $response = self::httpPatch('/api/rate/update', []);
        self::assertJson($response);
        $responseData = json_decode($response, true);
        self::assertArrayHasKey('error', $responseData);
        self::assertEquals([], $responseData['error']);
        self::assertArrayHasKey('status', $responseData);
        self::assertEquals('succeed', $responseData['status']);
    }
}
