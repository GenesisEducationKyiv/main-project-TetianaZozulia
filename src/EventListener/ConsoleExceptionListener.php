<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Command\Command;

class ConsoleExceptionListener
{
    public function onConsoleError(ConsoleErrorEvent $event)
    {
        $exception = $event->getError();
        $event->getOutput()->writeln('<error>Catch exception: '. $exception->getMessage() . '</error>' );
        $event->setExitCode(Command::FAILURE);
        die(Command::FAILURE);
    }
}
