<?php

declare(strict_types=1);

namespace App\Tests\Integration\Module\Rate\Service\BusinessCase;

use App\Module\Rate\Service\BusinessCase\UpdateRateBusinessCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UpdateCurrencyRateBusinessCaseTest extends KernelTestCase
{
    public function testExpectsRewriteFile(): void
    {
        /** @var UpdateRateBusinessCase $businessCase */
        $businessCase = self::getContainer()->get(UpdateRateBusinessCase::class);
        $businessCase->execute();

        self::assertEquals(
            date("Y-m-d H:i.", time()),
            date ("Y-m-d H:i.", filemtime('./tests/data/rate_btc_usd.json'))
        );
    }
}
