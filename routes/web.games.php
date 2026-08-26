<?php

declare(strict_types=1);

use App\Enums\SourceName as Source;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'games', 'as' => 'games'], function () {
    Route::get('/', [GameController::class, 'showcase'])->name('.showcase');
    Route::get('/{source}', [GameController::class, 'index'])->whereIn('source', Source::cases());
    Route::get('/{source}/latest', [GameController::class, 'latest'])->whereIn('source', Source::cases())->name('.latest');
//    Route::group(['prefix' => '{game}'], function () {
//        Route::get('/', [GameController::class, 'redirect'])->name('.redirect');
//        Route::group(['prefix' => '{slug}', 'middleware' => ['redirect.gameslug']], function () {
//            Route::get('/', [GameController::class, 'show'])->name('.show');
//            Route::group(['prefix' => 'votes', 'as' => '.votes', 'middleware' => ['auth']], function () {
//                Route::get('/', [GameVoteController::class, 'index']);
//                Route::post('/', [GameVoteController::class, 'store'])->name('.store');
//            });
//            Route::get('/reviews', [GameReviewController::class, 'index'])->name('.reviews');
//            Route::group(['prefix' => 'comments', 'as' => '.comments'], function () {
//                Route::get('/', [GameCommentController::class, 'index']);
//            }); # comments
//        });
//    }); # entity
}); # games
