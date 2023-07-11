<?php declare(strict_types=1);

namespace App\Tests\Unit\Storage;

use App\Storage\FileStorage\FileStorage;
use PHPUnit\Framework\TestCase;

class FileServiceTest extends TestCase
{
    public function testExpectsFileWrite(): void
    {
        $fileService = new FileStorage('./tests/data/');
        $fileService->write(
            'rate.json',
            '{"fromCurrencyName":"USD","toCurrencyName":"BTC","rate":30097.489587,"updateAt":1687503426}'
        );
        self::assertFileExists('./tests/data/rate.json');
    }

    public function testExpectsFileRead(): void
    {
        $fileService = new FileStorage('./tests/data/');
        $content = $fileService->read('rate.json');
        $data = '{"fromCurrencyName":"USD","toCurrencyName":"BTC","rate":30097.489587,"updateAt":1687503426}';
        self::assertEquals($data, $content);
    }

    public function testExpectsFileDelete(): void
    {
        $fileService = new FileStorage('./tests/data/');
        $fileService->delete('rate.json');
        self::assertFileDoesNotExist('./tests/data/rate.json');
    }
}
