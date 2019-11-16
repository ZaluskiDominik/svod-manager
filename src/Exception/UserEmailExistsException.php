<?php

namespace App\Exception;

use RuntimeException;

abstract class UserEmailExistsException extends RuntimeException
{
    /** @var string */
    protected $userType = 'user';

    public function __construct(string $email)
    {
        parent::__construct($this->userType . ' with email "' . $email . '" already exists!');
    }
}
