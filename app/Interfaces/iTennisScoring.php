<?php

namespace App\Interfaces;

/**
 * Interface Tennis Scoring Table
 */
interface iTennisScoring
{
    const OU = '0';
    const TEN = '10';
    const FIFTEEN = '15';
    const THIRTY = '30';
    const FOURTY = '40';
    const ADVANCE = 'Ad';
    const GAME = 'Game';   

    /**
     * Adds a point to player tennis' scoring table
     * @param string $current
     * @return string
     */
    public static function addScore(string $current):string;

    /**
     * Removes a point to player tennis' scoring table
     * @param string $current
     * @return string
     */
    public static function removeScore(string $current):string;
}