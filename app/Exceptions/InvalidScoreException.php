<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception for scoring used is not a valid tennis score.
 */
class InvalidScoreException extends Exception
{
    /**
     */
    public function __construct()
    {
        $this->message = "Scoring used is not a valid tennis score";
    }
}
