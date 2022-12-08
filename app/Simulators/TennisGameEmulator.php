<?php

namespace App\Simulators;

use App\Interfaces\iGameEmulator;
use App\Models\Player;

/**
 * Static class to emulate a tennis game.
 */
class TennisGameEmulator implements iGameEmulator{

    const ABILITY_MISTAKE = 1;
    const ABILITY_AMAZING = 100;

    /**
     * Emulate a tennis game and return a winner player.
     * @param object $player1
     * @param object $player2
     * @return object
     */
    public static function emulate(Object $player1, Object $player2):Object{
        $player1ratio = self::calculateAbilityCheck($player1);
        $player2ratio = self::calculateAbilityCheck($player2);

        if($player1ratio === $player2ratio){
            $gameWinner = self::emulate($player1, $player2);
        }else{
            $gameWinner = ($player1ratio > $player2ratio ? $player1 : $player2);
        }

        return $gameWinner;
    }

    /**
     * Calculate a ratio for a player in based its abilities and skills and luck.
     * @param Player $player A tennis Player Model
     * @return float|int
     */
    protected static function calculateAbilityCheck(Player $player):float|int
    {

        $uncertainty = self::rollDie();
        
        if($uncertainty === self::ABILITY_MISTAKE){
            return 0;

        }elseif($uncertainty === self::ABILITY_AMAZING){
            return 100;
        }

        $ability = self::getAbility($player->level);
        $modifiers = self::getSkillsModifiers($player->skills);
        
        return $ability+$modifiers+$uncertainty;
    }

    /**
     * Get profencies from a list of skills.
     * @param string $skills
     * @return float
     */
    protected static function getSkillsModifiers(string $skills):float{
        $skills = json_decode($skills);
        $modifiers = 0;

        foreach ($skills as $profency => $value) {
            $modifiers += $value;
        }

        return (float)$modifiers;
    }

    /**
     * Get ability level rounded.
     * @param int $level
     * @return float
     */
    protected static function getAbility(int $level):float{
        return round($level/10);
    }

    /**
     * Get a luck factor in a game.
     * @return int
     */
    protected static function rollDie():int{
        return rand(self::ABILITY_MISTAKE, self::ABILITY_AMAZING);
    }

    /**
     * Generate a random float. (it's from php.net)
     * @param mixed $min
     * @param mixed $max
     * @return float
     */
    protected static function randomFloat($min = 0, $max = 1):float {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
}
