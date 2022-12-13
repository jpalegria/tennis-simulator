<?php

namespace App\Simulators;

use App\Exceptions\InvalidBase2PlayersListException;
use App\Interfaces\iScoreable;
use App\Models\Player;
use App\Interfaces\iTournamentSimulator;
use App\Utils\Base2Util;

/**
 * Simulator for a tennis's compention
 */
class TennisTournament implements iTournamentSimulator, iScoreable
{
    use Base2Util;
    protected array $players;
    protected array $scores;
    protected array $fixture;
    protected array $matches;
    protected $winner = 0;

    /**
     * A tennis tournament
     * @param array $players
     */
    public function __construct(array $players)
    {
        $this->setPlayers($players);
        $this->scores = [];
        $this->fixture = [];
    }

    /**
     * Simulate a tennis tournament and return a winner player.
     * @return Player
     */
    public function simulate(): Player
    {
        $players =  $this->drawMatches($this->players);
        $this->winner = $this->challengePlayoffs($players);

        return $this->winner;
    }

    /**
     * Shuffle the player's list
     * @param array $playersList
     * @return array
     */
    protected function drawMatches(array $playersList): array
    {
        $fixture = $playersList;
        shuffle($fixture);
        return $fixture;
    }

    /**
     * Run the tennis tournament with a players list.
     * @param array $players
     * @return Player|null
     */
    protected function challengePlayoffs(array $players): ?Player
    {
        if (count($players) === 1) {
            return $players[0];
        }

        $winnersList = [];
        $this->registerMatchesInFixture($players);

        for ($i=0; $i < count($players); $i++) {
            $player1 = $players[$i];
            $player2 = $players[++$i];
            $winner = $this->challenge($player1, $player2);
            $winnersList[] = $winner;
        }

        return $this->challengePlayoffs($winnersList);
    }

    /**
     * Run the challenge between two players
     * @param Player $player1
     * @param Player $player2
     * @return Player
     */
    protected function challenge(Player $player1, Player $player2)
    {
        $match = new TennisMatch($player1, $player2);
        $winner = $match->simulate();
        $this->matches[] = $match->getScores();

        return $winner;
    }

    /**
     * Sign the players in the tournament
     * @param array $players
     * @return self
     */
    public function setPlayers(array $players): self
    {
        if (!$this->isBase2($players)) {
            throw new InvalidBase2PlayersListException();
        }

        $this->players = $players;
        return $this;
    }

    /**
     * Adds matches' round in the tournament fixture to log
     * @param array $players
     * @return void
     */
    protected function registerMatchesInFixture(array $players): void
    {
        $matches = [];
        $counterMatch = 1;

        for ($i=0; $i < count($players); $i++) {
            $matches[] = [$players[$i]->id, $players[++$i]->id];
            $counterMatch++;
        }

        $this->fixture['rounds'][] = $matches;
    }

    /**
     * @inheritDoc
     * @return string|array
     */
    public function getScores(): string|array
    {
        $this->scores['tournament']['fixture'] = $this->fixture;
        $this->scores['tournament']['matches'] = $this->matches;
        $this->scores['tournament']['winner'] = $this->winner->id;

        return $this->scores;
    }
}
