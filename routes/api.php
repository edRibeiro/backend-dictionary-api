<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('/signin', [AuthController::class, 'signin'])->name('signin');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function () {
    Route::get('/me', [UserController::class, 'me'])->middleware('auth:api')->name('user.me');
    Route::get('/me/history', [UserController::class, 'history'])->middleware('auth:api')->name('user.me.history');
    Route::get('/me/favorites', [UserController::class, 'favorites'])->middleware('auth:api')->name('user.me.favorites');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'entries'
], function () {
    Route::prefix('en')->group(function () {
        Route::get('/', [WordController::class, 'index'])->middleware('auth:api')->name('words.index');
        Route::get('/{word}', [WordController::class, 'show'])->middleware('auth:api')->name('words.show');
    });
});
