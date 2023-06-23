<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Mailer;

use App\Model\Email;
use App\Model\Mail\CurrencyMail;
use App\Service\Mailer\Mailer;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerTest extends TestCase
{
    public function testExpectsEmailSend(): void
    {
        $symfonyMailer = $this->createMock(MailerInterface::class);
        $symfonyMailer->expects($this->once())->method('send');

        $logger = $this->createMock(LoggerInterface::class);

        $mailer = new Mailer(
            $symfonyMailer,
            $logger
        );
        $mail = new CurrencyMail(
            'TEST Current currency for USD vs BTC' ,
            'test.test@gmail.com',
            'TEST Current currency for USD vs BTC'
        );
        $mail->setTo(new Email('emailTo@test.test'));
        $mail->setTxt('{"fromCurrencyName":"USD","toCurrencyName":"BTC","rate":30097.489587,"updateAt":1687503426}');
        $mailer->send($mail);
    }

    public function testExpectsEmailBatchSend(): void
    {
        $symfonyMailer = $this->createMock(MailerInterface::class);
        $symfonyMailer->expects($this->exactly(2))->method('send');

        $logger = $this->createMock(LoggerInterface::class);

        $mailer = new Mailer(
            $symfonyMailer,
            $logger
        );
        $mail = new CurrencyMail(
            'TEST Current currency for USD vs BTC' ,
            'test.test@gmail.com',
            'TEST Current currency for USD vs BTC'
        );
        $mail->setTxt('{"fromCurrencyName":"USD","toCurrencyName":"BTC","rate":30097.489587,"updateAt":1687503426}');
        $mailer->batchSend($mail, ['test1@test.test', 'test2@test.test']);
    }
}
