<?php

namespace App\Exception;

use RuntimeException;

class UndefinedEventQueueException extends RuntimeException
{
    public function __construct(string $queue)
    {
        parent::__construct('Queue "' . $queue . '" does not exist');
    }
}
