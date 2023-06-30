<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Mailer;

use App\Model\Mail\CurrencyMail;
use App\Model\Topic;
use App\Service\Mailer\MailFactory;
use PHPUnit\Framework\TestCase;

class CurrencyMailCreateTest extends TestCase
{
    public function testExpectsCreateCurrencyMail(): void
    {
        $currencyMail = new CurrencyMail(
            'TEST Current currency for USD vs BTC' ,
            'test.test@gmail.com',
            'TEST Current currency for USD vs BTC'
        );

        $mailFactory = new MailFactory($currencyMail);
        $actual = $mailFactory->create(new Topic('currency'));
        self::assertNotNull($actual);
        self::assertInstanceOf(CurrencyMail::class, $actual);
        self::assertEquals($currencyMail, $actual);
    }

    public function testExpectsInvalidArgumentException(): void
    {
        $currencyMail = new CurrencyMail(
            'TEST Current currency for USD vs BTC' ,
            'test.test@gmail.com',
            'TEST Current currency for USD vs BTC'
        );

        $mailFactory = new MailFactory($currencyMail);
        self::expectException(\InvalidArgumentException::class);
        $mailFactory->create(new Topic('undefined'));
    }
}
