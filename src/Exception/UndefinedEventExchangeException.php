<?php

namespace App\Exception;

use RuntimeException;

class UndefinedEventExchangeException extends RuntimeException
{
    public function __construct(string $exchange)
    {
        parent::__construct('Exchange "' . $exchange . '" does not exist');
    }
}
