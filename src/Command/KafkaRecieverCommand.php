<?php

declare(strict_types=1);

namespace App\Command;

use App\Logger\LogReceiverInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:log-kafka-receive')]
class KafkaRecieverCommand extends Command
{
    public function __construct(
        private LogReceiverInterface $receiver,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command start rabbit consumer to get logs')
            ->addArgument('routeKeys', InputArgument::REQUIRED, 'Error types separated by comma.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $routeKeys = $input->getArgument('routeKeys');
            $this->receiver->receive(explode(',', $routeKeys));
        } catch (\Throwable $exception) {
            $output->writeln('Catch exception: '. $exception->getMessage() );
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
