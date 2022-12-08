<?php

namespace App\Services;

use App\Exceptions\InvalidAlreadySimulatedTournamentException;
use App\Interfaces\iTennisService;
use App\Models\Tournament;
use App\Simulators\TennisTournament;

/**
 * Tennis Results Provider.
 */
class TennisService implements iTennisService
{
    /**
     * Simulate the tennis playoffs of a tournament and save the results to the entity. 
     * 
     * @param Tournament $tournament
     * @throws InvalidAlreadySimulatedTournamentException 
     * @return bool|array|object
     */
    public function play(Tournament $tournament): bool|array|object
    {
        if(!empty($tournament->champion)){
            throw new InvalidAlreadySimulatedTournamentException();
        }

        $tournamentSimulator = new TennisTournament($tournament->players->all());
        $champion = $tournamentSimulator->simulate();
        $results = $tournamentSimulator->getScores();
        $tournament->champion = $champion->id;
        $tournament->results = $results;

        return $tournament->save();
    }
}