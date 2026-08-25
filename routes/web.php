<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $g = \App\Models\Game::find(3520);
    $h = app(\App\Services\HistoryService::class);
    dd($h->getFieldTimeline($g, 'cis_score', now()->subMonth()));
    return view('welcome');
});
