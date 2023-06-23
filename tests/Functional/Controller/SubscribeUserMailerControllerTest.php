<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\AbstractWebTestCase;

class SubscribeUserMailerControllerTest extends AbstractWebTestCase
{
    /**
     * @dataProvider expectsResultStatusCodeProvider
     */
    public function testExpectsResultStatusCode(array $data, $expected): void
    {
        self::httpPost('/api/subscribe', $data);
        self::assertResponseStatusCodeSame($expected);
    }

    public function expectsResultStatusCodeProvider(): iterable
    {
        yield [
            ['email' => 'test2.test@gmail.com', 'topic' => 'currency'],
            200
        ];
        yield [
            ['email' => 'test2.test@gmail.com'],
            200
        ];
        yield [
            ['email' => 'test2.tcom'],
            400
        ];
        yield [
            ['topic' => 'currency'],
            400
        ];
    }
}
