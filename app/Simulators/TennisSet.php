<?php

namespace App\Simulators;

use App\Interfaces\iScoreable;
use App\Interfaces\iSetSimulator;
use App\Models\Player;

/**
 * Simulator for a tennis's set
 */
class TennisSet implements iSetSimulator, iScoreable
{
    protected Player $player1;
    protected Player $player2;
    protected $games = [];
    protected array $players = [];
    protected int $winner = 0;
    protected array $score = [];
    protected array $results = [];

    /**
     * A tennis set
     * @param mixed $player1
     * @param mixed $player2
     */
    public function __construct($player1, $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->players[] = $player1->id;
        $this->players[] = $player2->id;
        $this->games[$this->players[0]] = 0;
        $this->games[$this->players[1]] = 0;
    }

    /**
     * Simulate a tennis set and return a winner player.
     * @return Player
     */
    public function simulate(): Player
    {
        while (!$this->isfinished()) {
            $game = new TennisGame($this->player1, $this->player2);
            $winner = $game->simulate();
            $this->results[] = $game->getScores();
            $this->gameTo($winner);
        }

        return $winner;
    }

    /**
     * It brings a Game to player's score and check if player win the set.
     * @param Player $player
     * @return array<int>|bool Return the score or False if exists a winner.
     */
    public function gameTo(Player $player)
    {
        if ($this->isfinished()) {
            return false;
        }

        $this->addGame($player);
        $setGames = $this->games[$player->id];

        if ($this->isWinner($setGames)) {
            $this->finish($player);
            return true;
        }

        return $this->games;
    }

    /**
     * It adds a Game to player's score and return scoring.
     * @param Player $player
     * @return void
     */
    public function addGame(Player $player)
    {
        $this->games[$player->id]++;
    }

    /**
     * It indicates if the game finished and exists a game's winner.
     * @return bool
     */
    public function isfinished(): bool
    {
        return (bool) $this->winner;
    }

    /**
     * It indicates if scoring is enought to win.
     * @param mixed $setGames
     * @return bool
     */
    public function isWinner(string|int $setGames): bool
    {
        return ($setGames === 7) or ($setGames === 6 and abs($this->games[$this->players[0]] - $this->games[$this->players[1]]) >= 2);
    }

    /**
     * It makes a player winner.
     * @param Player $winner
     * @return void
     */
    public function finish(Player $winner)
    {
        $this->winner = $winner->id;
    }

    /**
     * @inheritDoc
     * @return array|string
     */
    public function getScores(): array|string
    {
        $this->score['set']['games'] = $this->games;
        $this->score['set']['results'] = $this->results;
        $this->score['set']['winner'] = $this->winner;

        return $this->score;
    }
}
