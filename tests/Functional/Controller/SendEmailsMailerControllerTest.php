<?php declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\AbstractWebTestCase;

class SendEmailsMailerControllerTest extends AbstractWebTestCase
{
    public function testExpects200(): void
    {
        self::httpPost('/api/sendEmails', []);
        self::assertResponseStatusCodeSame(200);
    }
}
