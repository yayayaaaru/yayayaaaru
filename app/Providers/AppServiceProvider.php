<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Tag;
use App\Repositories\HistoryRepository;
use App\Repositories\Contracts\HistoryRepository as HistoryRepositoryContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HistoryRepositoryContract::class, HistoryRepository::class);
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
            'category' => Category::class,
        ]);
    }
}
