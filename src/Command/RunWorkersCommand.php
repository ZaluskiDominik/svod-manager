<?php

namespace App\Command;

use App\Worker\AbstractWorker;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunWorkersCommand extends Command
{
    protected static $defaultName = 'app:workers:run';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->waitForRabbit();

        $workersData = require __DIR__ . '/../../config/workers.php';
        foreach ($workersData as $workerData) {
            if (!pcntl_fork()) {
                $workerFQN = '\\' . $workerData['worker'];
                /** @var AbstractWorker $worker */
                $worker = new $workerFQN($workerData['queue']);
                $worker->start();
                exit();
            }
            $output->writeln($workerData['worker'] . ' running for queue ' . $workerData['queue']);
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
