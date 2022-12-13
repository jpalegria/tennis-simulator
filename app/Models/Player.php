<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\Genre;

class Player extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'skills' => 'array'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot'
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
}
