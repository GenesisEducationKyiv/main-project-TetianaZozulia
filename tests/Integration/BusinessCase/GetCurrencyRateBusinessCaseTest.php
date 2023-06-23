<?php declare(strict_types=1);

namespace App\Tests\Integration\BusinessCase;

use App\Model\Rate;
use App\Service\BusinessCase\GetRateBusinessCase;
use App\Service\Storage\FileService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetCurrencyRateBusinessCaseTest extends KernelTestCase
{
    public function testExpectsGetRateModel(): void
    {
        /** @var GetRateBusinessCase $businessCase */
        $businessCase = self::getContainer()->get(GetRateBusinessCase::class);
        $actualRate = $businessCase->execute();

        self::assertNotNull($actualRate);
        self::assertInstanceOf(Rate::class, $actualRate);
        self::assertEquals('USD', $actualRate->getFromCurrencyName()->toString());
        self::assertEquals('BTC', $actualRate->getToCurrencyName()->toString());
    }

    public function testExpectsSavedFile(): void
    {
        $fileService = new FileService('./tests/data/');
        $fileService->delete('rate.json');

        /** @var GetRateBusinessCase $businessCase */
        $businessCase = self::getContainer()->get(GetRateBusinessCase::class);
        $businessCase->execute();

        $actualContent = $fileService->read('rate.json');

        self::assertNotFalse($actualContent);
        self::assertNotEmpty($actualContent);
    }
}
