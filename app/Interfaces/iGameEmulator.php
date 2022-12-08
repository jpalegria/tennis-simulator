<?php

namespace App\Interfaces;

interface iGameEmulator
{
    /**
     * Emulate a game.
     * @param object $player1
     * @param object $player2
     * @return object
     */
    public static function emulate(Object $player1, Object $player2): Object;
}