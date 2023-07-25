<?php

declare(strict_types=1);

namespace App\Command;

use App\Broker\Rabbit\RabbitBroker;
use App\Broker\ReceiverInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:log-receive')]
class LogRecieverCommand extends Command
{
    private array $brokerList = [];
    protected function configure(): void
    {
        $this
            ->setHelp('This command start consumer to get logs')
            ->addArgument('routeKeys', InputArgument::REQUIRED, 'Error types separated by comma.')
            ->addOption(
                'brokerName',
                null,
                InputOption::VALUE_OPTIONAL,
                'From what broker do you want to read?',
                RabbitBroker::BROKER_NAME
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
//        try {
            $brokerName = $input->getOption('brokerName');
            $receiver = $this->getReceiver($brokerName);
            $routeKeys = $input->getArgument('routeKeys');
            $receiver->receive(explode(',', $routeKeys));
//        } catch (\Throwable $exception) {
//            $output->writeln('Catch exception: '. $exception->getMessage() );
//            return Command::FAILURE;
//        }
        return Command::SUCCESS;
    }

    public function attachBroker(string $name, ReceiverInterface $receiver)
    {
        $this->brokerList[$name] = $receiver;
    }

    public function getReceiver(string $brokerName): ReceiverInterface
    {
        if (array_key_exists($brokerName, $this->brokerList)) {
            return $this->brokerList[$brokerName];
        }
        throw new \InvalidArgumentException(sprintf('Undefined broker name "%s"', $brokerName));
    }
}
