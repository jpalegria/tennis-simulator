<?php

use App\Simulators\TennisGame;
use App\Simulators\TennisMatch;
use App\Simulators\TennisSet;
use App\Simulators\TennisTournament;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * HEALTH CHECK ENDPOINTS
 */
Route::get('/ping', function (Request $request) {
    return response('{"code":200,"message":"pong"}');
});

/**
 * PLAYER ENDPOINTS
 */
Route::get('/players', [PlayerController::class, 'index']);
Route::get('/players/{id}', [PlayerController::class, 'show']);
Route::post('/players', [PlayerController::class, 'store']);
Route::delete('/players/{id}', [PlayerController::class, 'destroy']);

/**
 * TOURNAMENT ENDPOINTS
 */
Route::get('/tournaments', [TournamentController::class, 'index']);
Route::get('/tournaments/{id}', [TournamentController::class, 'show']);
Route::post('/tournaments', [TournamentController::class,'store']);
Route::put('/tournaments/{id}/simulate', [TournamentController::class,'play']);
