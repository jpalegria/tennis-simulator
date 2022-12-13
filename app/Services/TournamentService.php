<?php

namespace App\Services;

// use App\Enums\Genre;
use App\Enums\Genre;
use App\Exceptions\InvalidBase2PlayersListException;
use App\Exceptions\InvalidTournamentPlayersGenreException;
use App\Exceptions\UnsaveResourceException;
use App\Interfaces\iReaderFiltered;
use App\Interfaces\iTournamentService;
use App\Models\Player;
use App\Models\Tournament;
use App\Utils\Base2Util;
use Illuminate\Support\Facades\DB;

/**
 * Service Player Provider class
 */
class TournamentService implements iTournamentService, iReaderFiltered
{
    use Base2Util;

    public function __construct()
    {
    }

    /**
     * Create a new tournament entity.
     * @param string $name
     * @param array $players
     * @param string $genre
     * @throws UnsaveResourceException
     * @return Tournament|null
     */
    public function create(string $name, array $players, string $genre): ?Tournament
    {
        if (!$this->validatePlayers($genre, $players)) {
            return null;
        }
        try {
            DB::beginTransaction();

            $tournament = new Tournament();
            $tournament->name = $name;
            $tournament->genre = $genre;

            if ($tournament->save()) {
                $playersToSign = Player::whereIn('id', $players)->get();
                if (!$tournament->players()->saveMany($playersToSign)) {
                    throw new UnsaveResourceException();
                };
            } else {
                throw new UnsaveResourceException();
            }

            $tournament->refresh();
            DB::commit();

            return $this->readOne($tournament->id);
        } catch (\Throwable $e) {
            DB::rollback();
            error_log($e);
            return null;
        }
    }

    /**
     * Get a tournament entity
     * @param int|string $id
     * @return Tournament|null
     */
    public function readOne(int|string $id): ?Tournament
    {
        $tournament = Tournament::with('players')->find($id);

        return $tournament;
    }

    /**
     * Get all tournament entities availables.
     * @return array|null
     */
    public function readAll(): ?array
    {
        $tournaments = Tournament::with('players')->get();

        return $tournaments->all();
    }

    /**
     * Get all filtered tournaments entities.
     *
     * @param array $filters
     * @return array|null
     */
    public function readWithFilters(array $filters): ?array
    {
        $conditions = Tournament::getFilterConditions($filters);

        if (empty([$conditions])) {
            return [];
        }

        $tournaments = Tournament::with('players')->where($conditions)->get();

        return $tournaments->all();
    }

    /**
     * Validate players list and player entities to save in tournament.
     *
     * @param string $genre
     * @param array $players
     * @throws InvalidBase2PlayersListException
     * @throws InvalidTournamentPlayersGenreException
     * @return bool
     */
    protected function validatePlayers(string $genre, array $players)
    {
        if (!self::isBase2($players)) {
            throw new InvalidBase2PlayersListException();
        }

        $playersQuantity = Player::where('genre', Genre::mutate($genre))->whereIn('id', $players)->count();

        if ($playersQuantity !== count($players)) {
            throw new InvalidTournamentPlayersGenreException();
        }

        return true;
    }
}
