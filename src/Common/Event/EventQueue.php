<?php

namespace App\Common\Event;

use App\Exception\UndefinedEventQueueException;

class EventQueue
{
    public const SUBSCRIPTION = 'subscription';
    public const MAIL = 'mail';

    private const ALLOWED_QUEUES = [
        self::SUBSCRIPTION,
        self::MAIL
    ];

    /** @var string */
    private $queue;

    public function __construct(string $queue)
    {
        $this->setQueue($queue);
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function setQueue(string $queue): void
    {
        if (!in_array($queue, self::ALLOWED_QUEUES)) {
            throw new UndefinedEventQueueException($queue);
        }

        $this->queue = $queue;
    }
}
