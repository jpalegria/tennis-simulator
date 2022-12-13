<?php

namespace App\Services;

use App\Interfaces\iPlayerService;
use App\Enums\Genre;
use App\Models\Player;

/**
 * Service Player Provider
 */
class PlayerService implements iPlayerService
{
    /**
     * Create a new player entity
     *
     * @param string $name
     * @param integer $level
     * @param array $skills
     * @param string $genre
     * @return Player|null
     */
    public function create(string $name, int $level, array $skills, string $genre): ?Player
    {
        $player = new Player();
        $player->name = $name;
        $player->level = $level;
        $player->skills = $this->getSkillsByGenre($genre, $skills);
        $player->genre = $genre;

        return ($player->save() ? $player : null);
    }

    /**
     * Get a player entity
     *
     * @param integer|string $id
     * @return Player|null
     */
    public function readOne(int|string $id): ?Player
    {
        $player = Player::find($id);

        return $player;
    }

    /**
     * Get all player entities availables
     *
     * @return array|null
     */
    public function readAll(): ?array
    {
        $players = Player::all();

        return $players->all();
    }

    /**
     * Remove a player entity
     *
     * @param integer|string $id
     * @return boolean
     */
    public function delete(int|string $id): bool
    {
        return Player::destroy($id);
    }

    /**
     * Parse and get skills by genre
     *
     * @param string $genre
     * @param array $skills
     * @return array List[int] of skills
     */
    protected function getSkillsByGenre(string $genre, array $skills): array
    {
        $skillsData = [];

        if (Genre::female->name === $genre and array_key_exists('reaction', $skills)) {
            $skillsData['reaction'] = (int) $skills['reaction'];
        } elseif (Genre::male->name === $genre and array_key_exists('strength', $skills) and array_key_exists('velocity', $skills)) {
            $skillsData['strength'] = (int) $skills['strength'];
            $skillsData['velocity'] = (int) $skills['velocity'];
        }

        return $skillsData;
    }
}
