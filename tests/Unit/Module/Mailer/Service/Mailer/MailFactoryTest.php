<?php declare(strict_types=1);

namespace App\Tests\Unit\Module\Mailer\Service\Mailer;

use App\Module\Mailer\Model\Mail\CurrencyMail;
use App\Model\Topic as TopicModel;
use App\Enum\Topic;
use App\Module\Mailer\Service\Mailer\MailFactory;
use PHPUnit\Framework\TestCase;

class MailFactoryTest extends TestCase
{
    public function testExpectsCreateCurrencyMail(): void
    {
        $mailFactory = new MailFactory();
        $actual = $mailFactory->create(new TopicModel(Topic::Currency->value));
        self::assertNotNull($actual);
        self::assertInstanceOf(CurrencyMail::class, $actual);
    }

    public function testExpectsInvalidArgumentException(): void
    {
        $mailFactory = new MailFactory();
        self::expectException(\InvalidArgumentException::class);
        $mailFactory->create(new TopicModel('undefined'));
    }
}
