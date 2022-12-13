<?php

namespace App\Simulators;

use App\Simulators\TennisGameEmulator;
use App\Simulators\TennisPoints;
use App\Interfaces\iGameSimulator;
use App\Interfaces\iScoreable;
use App\Models\Player;

/**
 * Simulator for a tennis game.
 */
class TennisGame implements iGameSimulator, iScoreable
{
    protected Player $player1;
    protected Player $player2;
    protected $scoring = [];
    protected array $players = [];
    protected int $winner = 0;

    /**
     * A tennis game
     * @param Player $player1
     * @param Player $player2
     */
    public function __construct(Player $player1, Player $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->players[] = $player1->id;
        $this->players[] = $player2->id;
        $this->scoring[$this->players[0]] = TennisPoints::OU;
        $this->scoring[$this->players[1]] = TennisPoints::OU;
    }

    /**
     * Simulate a tennis game and return a winner player.
     * @return Player
     */
    public function simulate(): Player
    {
        while (!$this->isfinished()) {
            $winner = TennisGameEmulator::emulate($this->player1, $this->player2);
            $this->pointTo($winner);
        }

        return ($this->player1->id == $this->winner ? $this->player1 : $this->player2);
    }

    /**
     * It adds Point to player's score.
     * @param Player $player
     * @return void
     */
    public function addPoint(Player $player)
    {
        $this->scoring[$player->id] = TennisPoints::addScore($this->scoring[$player->id]);
    }

    /**
     * It brings to equals to forty-points to both players score.
     * @return void
     */
    public function equalsFortyPoint()
    {
        $this->scoring[$this->players[0]] = TennisPoints::FOURTY;
        $this->scoring[$this->players[1]] = TennisPoints::FOURTY;
    }

    /**
     * It brings a Point to player's score and check if player win.
     * @param Player $player
     * @return array<string>|bool
     */
    public function pointTo(Player $player)
    {
        $playerScore = $this->scoring[$player->id];

        if ($this->isfinished()) {
            return false;
        }

        if ($playerScore === TennisPoints::FOURTY and ($this->scoring[$this->players[0]] === $this->scoring[$this->players[1]])) {
            $this->addPoint($player);
        } elseif ($playerScore === TennisPoints::FOURTY and in_array(TennisPoints::ADVANCE, $this->scoring)) {
            $this->equalsFortyPoint();
        } elseif ($playerScore === TennisPoints::FOURTY or $playerScore === TennisPoints::ADVANCE) {
            $this->finish($player);
        // return TRUE;
        } else {
            $this->addPoint($player);
        }

        return $this->scoring;
    }

    /**
     * It indicates if exists a game's winner.
     * @return bool
     */
    public function isfinished(): bool
    {
        return (bool) $this->winner;
    }

    /**
     * It makes a player to winner.
     * @param Player $winner
     * @return void
     */
    public function finish(Player $winner)
    {
        $this->winner = $winner->id;
        $this->scoring[$this->winner] = TennisPoints::GAME;
    }

    /**
     * @inheritDoc
     * @return array|string
     */
    public function getScores(): array|string
    {
        return ['score' => $this->scoring];
    }
}
