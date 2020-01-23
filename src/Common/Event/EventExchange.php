<?php

namespace App\Common\Event;

use App\Exception\UndefinedEventExchangeException;

class EventExchange
{
    public const SUBSCRIPTIONS = 'subscriptions';
    public const MAILS = 'mails';

    private const ALLOWED_EXCHANGES = [
        self::SUBSCRIPTIONS,
        self::MAILS
    ];

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    public function __construct(string $name, string $type = 'direct')
    {
        $this->setName($name);
        $this->setType($type);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if (!in_array($name, self::ALLOWED_EXCHANGES)) {
            throw new UndefinedEventExchangeException($name);
        }

        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
