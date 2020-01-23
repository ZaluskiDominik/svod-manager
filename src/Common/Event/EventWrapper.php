<?php

namespace App\Common\Event;

use App\Common\Serialization\SerializableObjectInterface;
use JsonSerializable;

class EventWrapper implements SerializableObjectInterface, JsonSerializable
{
    /** @var string */
    private $eventClass;

    /** @var string */
    private $serializedJsonEvent;

    public function __construct(string $eventClass, string $serializedJsonEvent)
    {
        $this->eventClass = $eventClass;
        $this->serializedJsonEvent = $serializedJsonEvent;
    }

    public static function fromArray(array $data)
    {
        return new self($data['eventClass'], $data['serializedJsonEvent']);
    }

    public function toArray(): array
    {
        return [
            'eventClass' => $this->getEventClass(),
            'serializedJsonEvent' => $this->getSerializedJsonEvent()
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function getEventClass(): string
    {
        return $this->eventClass;
    }

    public function setEventClass(string $eventClass): void
    {
        $this->eventClass = $eventClass;
    }

    public function getSerializedJsonEvent(): string
    {
        return $this->serializedJsonEvent;
    }

    public function setSerializedJsonEvent(string $serializedJsonEvent): void
    {
        $this->serializedJsonEvent = $serializedJsonEvent;
    }

    public function getEventDataArray(): array
    {
        return json_decode($this->getSerializedJsonEvent(), true);
    }
}
