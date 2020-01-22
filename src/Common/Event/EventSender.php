<?php

namespace App\Common\Event;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EventSender
{
    /** @var AMQPStreamConnection */
    protected $connection;

    /** @var AMQPChannel */
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            $_ENV['RABBIT_HOST'],
            $_ENV['RABBIT_PORT'],
            $_ENV['RABBIT_USER'],
            $_ENV['RABBIT_PASSWORD']
        );

        $this->channel = $this->connection->channel();
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function send(string $queue, array $data)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage(json_encode($data), [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);
        $this->channel->basic_publish($msg, '', $queue);
    }
}
