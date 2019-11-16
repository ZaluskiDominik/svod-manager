<?php

namespace App\Exception;

class PublisherEmailExistsException extends UserEmailExistsException
{
    /** @var string */
    protected $userType = 'Publisher';

    public function __construct(string $email)
    {
        parent::__construct($email);
    }
}
