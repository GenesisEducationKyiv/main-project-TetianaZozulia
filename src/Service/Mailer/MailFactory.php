<?php declare(strict_types=1);

namespace App\Service\Mailer;

use App\Model\Mail\MailInterface;
use App\Model\Topic;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

class MailFactory implements MailFactoryInterface
{
    public function create(Topic $topic): MailInterface
    {
        $mailClassBaseName = $topic->getName() . 'Mail';
        $mailClass = self::MAIL_NAMESPACE . $mailClassBaseName;
        if (! class_exists($mailClass)) {
            throw new ClassNotFoundException($mailClass);
        }
        return new $mailClass();
    }
}
