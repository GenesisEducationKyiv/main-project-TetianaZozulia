<?php

declare(strict_types=1);

namespace App\Tests\Integration\Module\Rate\Service\BusinessCase;

use App\Module\Rate\Model\Rate;
use App\Module\Rate\Service\BusinessCase\GetRateBusinessCase;
use App\Storage\FileStorage\FileStorage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/** @codeCoverageIgnore  */
class GetCurrencyRateBusinessCaseTest extends KernelTestCase
{
    public function testExpectsGetRateModel(): void
    {
        /** @var GetRateBusinessCase $businessCase */
        $businessCase = self::getContainer()->get(GetRateBusinessCase::class);
        $actualRate = $businessCase->execute();

        self::assertNotNull($actualRate);
        self::assertInstanceOf(Rate::class, $actualRate);
        self::assertEquals('BTC', $actualRate->getFromCurrencyName()->toString());
        self::assertEquals('USD', $actualRate->getToCurrencyName()->toString());
    }

    public function testExpectsSavedFile(): void
    {
        $fileService = new FileStorage('./tests/data/');
        $fileService->delete('rate_btc_usd.json');

        /** @var GetRateBusinessCase $businessCase */
        $businessCase = self::getContainer()->get(GetRateBusinessCase::class);
        $businessCase->execute();

        $actualContent = $fileService->read('rate_btc_usd.json');

        self::assertNotFalse($actualContent);
        self::assertNotEmpty($actualContent);
    }
}
