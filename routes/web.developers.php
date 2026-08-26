<?php

declare(strict_types=1);

use App\Enums\SourceName as Source;
use App\Http\Controllers\DeveloperController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'developers', 'as' => 'developers'], function () {
    Route::get('/', [DeveloperController::class, 'showcase'])->name('.showcase');
    Route::get('/{source}', [DeveloperController::class, 'index'])->whereIn('source', Source::cases());
    Route::get('/{source}/latest', [DeveloperController::class, 'latest'])->whereIn('latest', Source::cases());
//    Route::group(['prefix' => '{developer}'], function () {
//        Route::get('/', [DeveloperController::class, 'redirect'])->name('.redirect');
//        Route::group(['prefix' => '{slug}', 'middleware' => ['redirect.developerslug']], function () {
//            Route::get('/', [DeveloperController::class, 'show'])->name('.show');
//            Route::get('/games', [DeveloperGameController::class, 'index'])->name('.games');
//            Route::group(['prefix' => 'comments', 'as' => '.comments'], function () {
//                Route::get('/', [DeveloperCommentController::class, 'index']);
//            }); # comments
//        });
//    }); # developer
}); # developers
