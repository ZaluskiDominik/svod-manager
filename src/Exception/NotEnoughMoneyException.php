<?php

namespace App\Exception;

use RuntimeException;

class NotEnoughMoneyException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Sorry, it appears that you have not enough money to purchase this subscription");
    }
}
