<?php declare(strict_types=1);

namespace App\Tests\Functional\Module\Subscription\Controller;

use App\Storage\FileStorage\FileStorage;
use App\Tests\Functional\AbstractWebTestCase;

class SubscribeUserMailerControllerTest extends AbstractWebTestCase
{
    public function setUp(): void
    {
        $testFileService = new FileStorage('./tests/data/');
        $testFileService->delete('/subscribers/currency_subscribers.json');
        parent::setUp();
    }

    /**
     * @dataProvider expectsResultStatusCodeProvider
     */
    public function testExpectsResultStatusCode(array $data, $expected): void
    {
        self::httpPost('/api/subscribe', $data);
        self::assertResponseStatusCodeSame($expected);
    }

    public static function expectsResultStatusCodeProvider(): iterable
    {
        yield [
            ['email' => 'test7.test@gmail.com', 'topic' => 'currency'],
            200
        ];
        yield [
            ['email' => 'test6.test@gmail.com'],
            200
        ];
        yield [
            ['email' => 'test7.tcom'],
            400
        ];
        yield [
            ['topic' => 'currency'],
            400
        ];
    }
}
