<?php

namespace App\Interfaces;

interface iScoreable{

    /**
     * Gets a scoring for a game.
     * @return string|array
     */
    public function getScores(): string|array;
}