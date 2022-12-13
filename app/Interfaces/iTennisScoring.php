<?php

namespace App\Interfaces;

/**
 * Interface Tennis Scoring Table
 */
interface iTennisScoring
{
    public const OU = '0';
    public const TEN = '10';
    public const FIFTEEN = '15';
    public const THIRTY = '30';
    public const FOURTY = '40';
    public const ADVANCE = 'Ad';
    public const GAME = 'Game';

    /**
     * Adds a point to player tennis' scoring table
     * @param string $current
     * @return string
     */
    public static function addScore(string $current): string;

    /**
     * Removes a point to player tennis' scoring table
     * @param string $current
     * @return string
     */
    public static function removeScore(string $current): string;
}
