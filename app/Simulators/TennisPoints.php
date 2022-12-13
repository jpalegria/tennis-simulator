<?php

namespace App\Simulators;

use App\Exceptions\InvalidScoreException;
use App\Interfaces\iTennisScoring;

/**
 * Simulator for tennis's scoring table
 */
class TennisPoints implements iTennisScoring
{
    protected static array $scoringTable = [self::OU, self::TEN, self::FIFTEEN, self::THIRTY, self::FOURTY, self::ADVANCE, self::GAME];

    /**
     * @inheritDoc
     * @param string $current
     * @return string
     */
    public static function addScore(string $current): string
    {
        self::validateScoring($current);

        $index = array_search($current, self::$scoringTable);
        $score = $current;

        if ($index+1 < count(self::$scoringTable)) {
            $score = self::$scoringTable[$index+1];
        }

        return $score;
    }

    /**
     * @inheritDoc
     * @param string $current
     * @return string
     */
    public static function removeScore(string $current): string
    {
        self::validateScoring($current);

        $index = array_search($current, self::$scoringTable);
        $score = $current;

        if ($index-1 >= 0) {
            $score = self::$scoringTable[$index-1];
        }

        return $score;
    }

    /**
     * Validates the current score exists in scoring table
     * @param mixed $score
     * @throws InvalidScoreException
     * @return bool
     */
    protected static function validateScoring(string $score): bool
    {
        if (!in_array($score, self::$scoringTable)) {
            throw new InvalidScoreException();
        }

        return true;
    }
}
