<?php

namespace App\Command;

use App\Common\Event\EventExchange;
use App\Common\Event\EventQueue;
use App\Worker\AbstractWorker;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunWorkersCommand extends Command
{
    protected static $defaultName = 'app:workers:run';

    /** @var ContainerInterface */
    private $containerInterface;

    public function __construct(ContainerInterface $containerInterface)
    {
        parent::__construct();
        $this->containerInterface = $containerInterface;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->waitForRabbit();

        $workersData = require __DIR__ . '/../../config/workers.php';
        foreach ($workersData as $workerData) {
            if (!pcntl_fork()) {
                $exchange = new EventExchange($workerData['exchange']);
                $queue = new EventQueue($workerData['queue']);
                $workerClass = $workerData['worker'];
                /** @var AbstractWorker $worker */
                $worker = new $workerClass($this->containerInterface, $exchange, $queue, $workerData['events']);
                $worker->start();
                exit();
            }
            $output->writeln($workerData['worker'] . ' running for exchange ' . $workerData['exchange']);
        }
    }

    private function waitForRabbit()
    {
        while(true) {
            usleep(100);
            try {
                $conn = new AMQPStreamConnection(
                    $_ENV['RABBIT_HOST'],
                    $_ENV['RABBIT_PORT'],
                    $_ENV['RABBIT_USER'],
                    $_ENV['RABBIT_PASSWORD']
                );
            } catch (\Exception $e) {
                continue;
            }

            break;
        }
        $conn->close();
    }
}
