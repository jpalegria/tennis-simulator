<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('genre')->comment('Genres: male(0), female(1)');
            $table->foreignId('champion')->nullable();
            $table->json('results')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('champion')->references('id')->on('players');
        });

        Schema::create('player_tournament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id');
            $table->foreignId('tournament_id');

            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('tournament_id')->references('id')->on('tournaments');
           });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournaments_players');
        Schema::dropIfExists('tournaments');
    }
};
