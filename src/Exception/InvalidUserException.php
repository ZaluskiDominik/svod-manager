<?php

namespace App\Exception;

use RuntimeException;

class InvalidUserException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('User must be of instance CustomerEntity or PublisherEntity!');
    }
}
