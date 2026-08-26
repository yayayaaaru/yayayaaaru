<?php

declare(strict_types=1);

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tags', 'as' => 'tags'], function () {
    Route::get('/', [TagController::class, 'index']);
}); # tags
