<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception for a genre's players list is not the same torunament's genre.
 */
class InvalidTournamentPlayersGenreException extends Exception
{
    public function __construct() {
        $this->message = "Tournament's players must be same genre than tournament's genre attribute";
    }
}
