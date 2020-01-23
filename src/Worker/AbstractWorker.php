<?php

namespace App\Worker;

use App\Common\Event\EventExchange;
use App\Common\Event\EventQueue;
use App\Common\Event\EventWrapper;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

abstract class AbstractWorker
{
    /** @var AMQPStreamConnection */
    protected $connection;

    /** @var AMQPChannel */
    protected $channel;

    /** @var EventQueue */
    protected $queue;

    /** @var array */
    protected $events;

    public function __construct(EventExchange $exchange, EventQueue $queue, array $events)
    {
        $this->queue = $queue;
        $this->events = $events;
        $this->connection = new AMQPStreamConnection(
            $_ENV['RABBIT_HOST'],
            $_ENV['RABBIT_PORT'],
            $_ENV['RABBIT_USER'],
            $_ENV['RABBIT_PASSWORD']
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($queue->getName(), false, true, false, false);
        $this->channel->exchange_declare($exchange->getName(), $exchange->getType(), false, true, false);

        foreach ($events as $event) {
            $this->channel->queue_bind($queue->getName(), $exchange->getName(), $event);
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    abstract public function work(EventWrapper $eventWrapper);

    public function start()
    {
        $this->channel->basic_consume(
            $this->queue->getName(),
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
        try {
            $this->work(EventWrapper::fromArray(json_decode($msg->getBody(), true)));
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $this->channel->basic_ack($msg->getDeliveryTag());
        }
    }
}
