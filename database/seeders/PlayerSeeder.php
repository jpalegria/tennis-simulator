<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;



class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        foreach (range(1,10) as $i ) {
            $malePlayer = new Player();
            $malePlayer->name = fake()->firstName('male');
            $malePlayer->level = fake()->numberBetween(1, 100);
            $malePlayer->skills = json_encode(['strength' => random_int(1,10), 'velocity' =>random_int(1,10)]);
            $malePlayer->genre = 'male';
            $malePlayer->save();

            $femalePlayer = new Player();
            $femalePlayer->name = fake()->firstName('female');
            $femalePlayer->level = fake()->numberBetween(1, 100);
            $femalePlayer->skills = json_encode(['reaction' => random_int(1,10)]);
            $femalePlayer->genre = 'female';
            $femalePlayer->save();
        }

        DB::commit();

    }
}
