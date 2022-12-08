<?php

namespace App\Interfaces;

use App\Models\Player;

/**
 * Service Player Provider Interfaces.
 */
interface iPlayerService
{
    /**
     * Create a resource.
     * @param string $name
     * @param int $level
     * @param array $skills
     * @param string $genre
     * @return Player|null
     */
    public function create(string $name, int $level, array $skills, string $genre): ?Player;

    /**
     * Get only one resource.
     * @param int|string $id
     * @return Player|null
     */
    public function readOne(int|string $id): ?Player;

    /**
     * Get all resources availables.
     * @return array|null
     */
    public function readAll(): ?array;

    /**
     * Delete a resource.
     * @param int|string $id
     * @return bool
     */
    public function delete(int|string $id): bool;
}