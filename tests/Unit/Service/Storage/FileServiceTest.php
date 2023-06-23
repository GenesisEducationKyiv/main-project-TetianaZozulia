<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Storage;

use App\Service\Storage\FileService;
use PHPUnit\Framework\TestCase;

class FileServiceTest extends TestCase
{
    public function testExpectsFileRead(): void
    {
        $fileService = new FileService('./tests/data/');
        $fileService->write(
            'rate.json',
            '{"fromCurrencyName":"USD","toCurrencyName":"BTC","rate":30097.489587,"updateAt":1687503426}'
        );
        $actual = $fileService->read('rate.json');
        self::assertNotEmpty($actual);
        self::assertJson($actual);
        self::assertEquals(
            '{"fromCurrencyName":"USD","toCurrencyName":"BTC","rate":30097.489587,"updateAt":1687503426}',
            $actual
        );
    }

    public function testExpectsFileWriteContent(): void
    {
        $fileService = new FileService('./tests/data/');
        $fileService->delete('rate.json');
        $data = '{"fromCurrencyName":"USD","toCurrencyName":"BTC","rate":30097.489587,"updateAt":1687503426}';
        $fileService->write('rate.json', $data);
        $actual = $fileService->read('rate.json');
        self::assertEquals($data, $actual);
    }
}
