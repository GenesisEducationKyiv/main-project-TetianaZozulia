<?php declare(strict_types=1);

namespace App\Tests\Functional\Module\Rate\Controller;

use App\Tests\Functional\AbstractWebTestCase;

class UpdateRateCurrencyControllerTest extends AbstractWebTestCase
{
    public function testExpects200(): void
    {
        self::httpPatch('/api/rate/update', ['from' => 'btc', 'to' => 'usd']);
        self::assertResponseStatusCodeSame(200);
    }

    public function testExpectsEmptyResponse(): void
    {
        $response = self::httpPatch('/api/rate/update', ['from' => 'btc', 'to' => 'usd']);
        self::assertJson($response);
        self::assertEquals('{}', $response);
    }
}
