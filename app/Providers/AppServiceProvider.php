<?php

namespace App\Providers;

use App\Models\Developer;
use App\Models\Game;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'developer' => Developer::class,
            'game' => Game::class,
            'tag' => Tag::class,
        ]);
    }
}
