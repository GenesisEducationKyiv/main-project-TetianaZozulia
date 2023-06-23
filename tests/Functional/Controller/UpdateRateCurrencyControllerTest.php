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
}
