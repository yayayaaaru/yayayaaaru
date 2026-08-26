<?php

declare(strict_types=1);

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'categories', 'as' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
}); # categories
