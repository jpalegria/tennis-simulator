<?php

namespace App\Interfaces;

use App\Models\Player;
use App\Enums\Genre;
use App\Models\Tournament;

/**
 * Service Player Provider class
 */
interface iTournamentService
{
    /**
     * Create a resource.
     * @param string $name
     * @param array $players
     * @param string $genre
     * @return Tournament|null
     */
    public function create(string $name, array $players, string $genre): ?Tournament;

    /**
     * Get a only one resource.
     * @param int|string $id
     * @return Tournament|null
     */
    public function readOne(int|string $id): ?Tournament;

    /**
     * Get all resources availables.
     * @return array|null
     */
    public function readAll(): ?array;
}