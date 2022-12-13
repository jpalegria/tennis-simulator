<?php

namespace App\Interfaces;

use App\Models\Tournament;

interface iTennisService
{
    /**
     * Simulate a tennis tournament.
     * @param Tournament $tournament
     * @return bool
     */
    public function play(Tournament $tournament): bool|array|object;
}
