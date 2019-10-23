<?php

namespace App\Exception;

use RuntimeException;

class InvalidUserRoleException extends RuntimeException
{
    public function __construct(string $givenUserRole)
    {
        parent::__construct("User role '" . $givenUserRole . "' is invalid! 'customer' or 'publisher'
            are the only allowed user roles.");
    }
}
