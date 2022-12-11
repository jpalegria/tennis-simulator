<?php

namespace App\Models;

use App\Enums\Genre;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Tournament extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'results' => 'array'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    /**
     * Interact with the player's genre.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function genre(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Genre::from($value)->name,
            set: fn ($value) => Genre::mutate(strtolower($value))
        );
    }

    /**
     * Attribute serializable from Player Model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players(){
        //return $this->belongsToMany(Player::class, 'player_tournament', 'player_id','tournament_id'); //Alternative option
        return $this->belongsToMany(Player::class);
    }


    /**
     * Attribute format for createdAt
     * @return Attribute
     */
    public function createdAt(): Attribute{
        return Attribute::make(
            get: fn($value) => (new \DateTime($value))->format('Y-m-d')
        );
    }

    /**
     * Make a array of filters conditions with any next conditions:  name, genre, created_at, simulated, champion.
     * @param array $params
     * @return array
     */
    public static function getFilterConditions(array $params):array{
        $params = self::sanitizeFieldsFilter($params);

        $conditions = [];   

        if($params['name']){
            $conditions[] = ['name', 'like', '%' . $params['name'] . '%'];
        }

        if($params['genre']){
            $conditions[] = ['genre', Genre::mutate($params['genre'])];
        }

        if($params['created_at']){
            $conditions[] = ['created_at', 'like', $params['created_at'].'%'];
        }

        if($params['simulated'] !== NULL)
        {
            if($params['simulated']){
                $conditions[] = ['champion', '<>', ''];
            }else{
                $conditions[] = ['champion', NULL];
            }  
        }

        if($params['champion']){
            $conditions[] = ['champion', $params['champion']];
        }

        return $conditions;
    }

    /**
     * Sanitize and prepared fields filters
     * @param array $fields
     * @return array|bool|null
     */
    protected static function sanitizeFieldsFilter(array $fields){
        $definitions = [
            'name' => FILTER_SANITIZE_STRING,
            'genre' => FILTER_SANITIZE_STRING,
            'created_at' => FILTER_SANITIZE_STRING,
            'simulated' => FILTER_SANITIZE_NUMBER_INT,
            'champion' => FILTER_SANITIZE_NUMBER_INT
        ];

        return filter_var_array($fields, $definitions);
    }
}