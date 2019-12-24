<?php

namespace App\Exception;

use RuntimeException;

class CompanyExistsException extends RuntimeException
{
    public function __construct(string $company)
    {
        parent::__construct('Company "' . $company . '" already exists!');
    }
}
