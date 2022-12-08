<?php
namespace App\Enums;

/**
 * Genre Enumeration
 */
enum Genre:int
{
    case male = 0;
    case female = 1;

    /**
     * From string genre-name get the value
     * @param string $genre
     * @return int
     */
    public static function mutate(string $genre): int
    {
        return match($genre) {
            Genre::male->name => Genre::male->value,
            Genre::female->name => Genre::female->value,
        };
    }
}