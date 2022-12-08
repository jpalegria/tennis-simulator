<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception for resources that can not be saved it
 */
class UnsaveResourceException extends Exception
{
    /**
     */
    public function __construct() {
        $this->message = "Resource could not be persisted";
    }
}