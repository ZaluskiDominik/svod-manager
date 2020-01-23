<?php

namespace App\Common\Event;

use App\Exception\UndefinedEventQueueException;

class EventQueue
{
    public const SUBSCRIPTIONS = 'subscriptions';
    public const MAILS = 'mails';

    private const ALLOWED_QUEUES = [
        self::SUBSCRIPTIONS,
        self::MAILS
    ];

    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if (!in_array($name, self::ALLOWED_QUEUES)) {
            throw new UndefinedEventQueueException($name);
        }
        $this->name = $name;
    }
}
