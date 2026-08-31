<?php

use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    require __DIR__ . '/web.games.php';
    require __DIR__ . '/web.developers.php';
    require __DIR__ . '/web.categories.php';
    require __DIR__ . '/web.tags.php';
});

Route::get('/', function () {

    // --- Базовые мета-теги ---
    SEOMeta::setTitle(config('app.name'), false)->setDescription(\Illuminate\Support\Str::limit('Мы собираем и анализируем данные о тысячах браузерных игр: рейтинги, оценки игроков, динамику популярности, обновления и тренды. Наша цель — помочь вам выбрать лучшую игру или отследить состояние любимого проекта.', 160, ''));

    $developers = Cache::remember(cache_key('developers_today_latest_limit_7'), now()->addHours(1), function () {
        return \App\Models\Developer::whereDate('created_at', today())->latest('id')->limit(7)->get()->toArray();
    });

    $games = Cache::remember(cache_key('games_today_latest_limit_7'), now()->addHours(1), function () {
        return \App\Models\Game::with('developer')->whereDate('released_at', today())->latest('released_at')->limit(7)->get()->toArray();
    });

    $categories = Cache::remember(cache_key('categories_today_limit_10'), now()->addHours(6), function () {
        return \App\Models\Category::withCount([
            'games',
            'games as period_games_count' => static fn($query) => $query->whereBetween('released_at', [
                today()->startOfDay(),
                today()->endOfDay(),
            ]),
        ])->orderByDesc('period_games_count')->orderByDesc('games_count')->limit(10)->get()->toArray();
    });

    $tags = Cache::remember(cache_key('tags_today_limit_10'), now()->addHours(6), function () {
        return \App\Models\Tag::withCount([
            'games',
            'games as period_games_count' => static fn($query) => $query->whereBetween('released_at', [
                today()->startOfDay(),
                today()->endOfDay(),
            ]),
        ])->orderByDesc('period_games_count')->orderByDesc('games_count')->limit(10)->get()->toArray();
    });

    return view('web.index', compact('developers', 'games', 'categories', 'tags'));
});
