<?php

namespace App\Common\Event;

use App\Common\Serialization\SerializableObjectInterface;
use JsonSerializable;

abstract class AbstractEvent implements SerializableObjectInterface, JsonSerializable
{
    abstract public static function fromArray(array $data);

    abstract public function toArray(): array;

    abstract public function jsonSerialize();
}
