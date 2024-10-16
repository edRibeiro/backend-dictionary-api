<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
