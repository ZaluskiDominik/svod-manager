<?php

namespace App\Exception;

class CustomerEmailExistsException extends UserEmailExistsException
{
    /** @var string */
    protected $userType = 'Customer';

    public function __construct(string $email)
    {
        parent::__construct($email);
    }
}
