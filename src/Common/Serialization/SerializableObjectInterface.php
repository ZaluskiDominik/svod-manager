<?php

namespace App\Common\Serialization;

interface SerializableObjectInterface
{
    public static function fromArray(array $data);

    public function toArray(): array;
}
