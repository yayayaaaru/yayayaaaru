<?php

use Illuminate\Support\Facades\Route;

Route::group([], function () {
    require __DIR__ . '/web.games.php';
    require __DIR__ . '/web.developers.php';
    require __DIR__ . '/web.categories.php';
    require __DIR__ . '/web.tags.php';
});

Route::get('/', function () {
    $developers = \App\Models\Developer::latest('id')->limit(7)->get();

    $games = \App\Models\Game::with('developer')->whereDate('released_at', today())->latest('released_at')->limit(7)->get();

    $categories = \App\Models\Category::withCount([
        'games',
        'games as period_games_count' => function ($query) {
            $query->whereDate('released_at', today());
        },
    ])->orderByDesc('period_games_count')->orderByDesc('games_count')->limit(10)->get();

    $tags = \App\Models\Tag::withCount([
        'games',
        'games as period_games_count' => function ($query) {
            $query->whereDate('released_at', today());
        },
    ])->orderByDesc('period_games_count')->orderByDesc('games_count')->limit(10)->get();

    return view('web.index', compact('developers', 'games', 'categories', 'tags'));
});
