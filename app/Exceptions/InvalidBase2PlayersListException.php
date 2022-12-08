<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception for a players list that It doesn't have amount pair of tennis matches.
 */
class InvalidBase2PlayersListException extends Exception
{
    /**
     */
    public function __construct() {
        $this->message = "Quantity players in list must be base 2";
    }
}