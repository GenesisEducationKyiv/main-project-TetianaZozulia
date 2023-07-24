<?php declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractWebTestCase extends WebTestCase
{
    private static ?KernelBrowser $client = null;

    private const DEFAULT_HEADERS = [
        'CONTENT_TYPE' => 'application/json',
    ];

    protected function setUp(): void
    {
        self::$client = self::createClient();
    }

    public function httpGet(string $url, array $queryParams = []): string
    {
        self::$client->request('GET', $url, $queryParams, [], self::DEFAULT_HEADERS);
        return self::$client->getResponse()->getContent();
    }

    public function httpPatch(string $url, array $request): string
    {
        $request = json_encode($request);
        self::$client->request('PATCH', $url, [], [], self::DEFAULT_HEADERS, $request);
        return static::$client->getResponse()->getContent();
    }

    public function httpPost(string $url, array $request): string
    {
        $request = json_encode($request);
        self::$client->request('POST', $url, [], [], self::DEFAULT_HEADERS, $request);
        return static::$client->getResponse()->getContent();
    }
}
