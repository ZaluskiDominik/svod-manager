<?php

namespace App\Worker;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

abstract class AbstractWorker
{
    /** @var AMQPStreamConnection */
    protected $connection;

    /** @var AMQPChannel */
    protected $channel;

    /** @var string */
    protected $queue;

    public function __construct(string $queue)
    {
        $this->connection = new AMQPStreamConnection(
            $_ENV['RABBIT_HOST'],
            $_ENV['RABBIT_PORT'],
            $_ENV['RABBIT_USER'],
            $_ENV['RABBIT_PASSWORD']
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($queue, false, true, false, false);
        $this->queue = $queue;
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    abstract public function work(array $data);

    public function start()
    {
        $this->channel->basic_consume(
            $this->queue,
            '',
            false,
            false,
            false,
            false,
            [$this, 'preWork']
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function preWork(AMQPMessage $msg)
    {
        $this->work(json_decode($msg->getBody(), true));
    }
}
