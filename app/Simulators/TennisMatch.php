<?php

namespace App\Simulators;

use App\Interfaces\iMatchSimulator;
use App\Interfaces\iScoreable;
use App\Models\Player;

/**
 * Simulator for a tennis match
 */
class TennisMatch implements iMatchSimulator, iScoreable{

    protected Player $player1;
    protected Player $player2;
    protected array $sets = [];
    protected array $players = [];
    protected int $winner = 0;
    protected int $setsToWin = 3;
    protected array $score = [];
    protected array $results = [];

    /**
     * A tennis match
     *
     * @param Player $player1
     * @param Player $player2
     * @param boolean $bestOfFiveSet
     */
    public function __construct(Player $player1, Player $player2, bool $bestOfFiveSet = False)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->players[] = $player1->id;
        $this->players[] = $player2->id;

        $this->sets[$this->players[0]] = 0;
        $this->sets[$this->players[1]] = 0;

        $this->setsToWin = $bestOfFiveSet ? 3 : 2;
    }

    /**
     * Simulate a tennis match and return a winner player.
     * @return Player
     */
    public function simulate():Player{

        while(!$this->isfinished()){
            $set = new TennisSet($this->player1, $this->player2);
            $winner = $set->simulate();
            $this->results[] = $set->getScores();
            $this->setTo($winner);

        }
        
        return $winner;
    }

    /**
     * It brings a Set to player's score and check if player win the match.
     * @param Player $player
     * @return array
     */
    public function setTo(Player $player):array{
        $playerSetsWon = $this->addSetTo($player);

        if( $this->isWinningSet($playerSetsWon) ){
            $this->finish($player);
        }

        return $this->sets;
    }

    /**
     * It adds a Set to player's score and return it.
     * @param Player $player
     * @return int
     */
    public function addSetTo(Player $player): int{
        return ++$this->sets[$player->id];
    }

    /**
     * It indicates if the match finished and exists a match's winner.
     * @return bool
     */
    public function isfinished():bool{
        return (bool) $this->winner;
    }

    /**
     * It makes a player to winner.
     * @param Player $winner
     * @return void
     */
    public function finish(Player $winner){
        $this->winner = $winner->id;
    }

    /**
     * It indicates if amount set is enought to win.
     * @param int $setsWon
     * @return bool
     */
    public function isWinningSet(int $setsWon):bool{
        return $this->setsToWin === $setsWon;
    }

	/**
     * @inheritDoc
	 * @return array|string
	 */
	public function getScores(): array|string {
        $this->score['match']['players'] = $this->players;
        $this->score['match']['sets'] = $this->sets;
        $this->score['match']['results'] = $this->results;
        $this->score['match']['winner'] = $this->winner;

        return $this->score;
	}
}