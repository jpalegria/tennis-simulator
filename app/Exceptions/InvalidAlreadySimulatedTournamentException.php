<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception for a game simulated that It has a champion defined.
 */
class InvalidAlreadySimulatedTournamentException extends Exception
{
    public function __construct() {
        $this->message = "Tournament is already simulated and It has a champion";
    }
}
