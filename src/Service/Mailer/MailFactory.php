<?php declare(strict_types=1);

namespace App\Service\Mailer;

use App\Model\Mail\CurrencyMail;
use App\Model\Mail\MailInterface;
use App\Model\Topic;

class MailFactory implements MailFactoryInterface
{
    public function __construct(
        private CurrencyMail $currencyMail
    ) {
    }

    public function create(Topic $topic): MailInterface
    {
        $varName = $topic->getName() . 'Mail';
        if (!isset($varName)) {
            throw new \InvalidArgumentException('Undefined topic');
        }
        return $this->$varName;
    }
}
