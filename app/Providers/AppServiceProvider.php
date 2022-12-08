<?php

namespace App\Providers;

use App\Interfaces\iTournamentService;
use App\Interfaces\iTennisService;
use App\Interfaces\iHttpResponseService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\iPlayerService;
use App\Services\HttpResponseService;
use App\Services\PlayerService;
use App\Services\TournamentService;
use App\Services\TennisService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(iPlayerService::class, PlayerService::class);
        $this->app->bind(iTournamentService::class, TournamentService::class);
        $this->app->bind(iTennisService::class, TennisService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
