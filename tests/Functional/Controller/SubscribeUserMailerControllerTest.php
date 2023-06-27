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

    public function testExpectsNotEmptyResponse(): void
    {
        $response = self::httpPost('/api/subscribe', ['email' => 'test2.test@gmail.com', 'topic' => 'currency']);
        self::assertJson($response);
        $responseData = json_decode($response, true);
        self::assertArrayHasKey('error', $responseData);
        self::assertEquals([], $responseData['error']);
        self::assertArrayHasKey('status', $responseData);
        self::assertEquals('succeed', $responseData['status']);
    }

    public static function expectsResultStatusCodeProvider(): iterable
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
