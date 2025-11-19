<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| SNSログイン 
|--------------------------------------------------------------------------
*/

    # LINEログイン
    Route::get('/login/line',
    [Controllers\Auth\LoginLineController::class, 'index'])
    ->name('login.line');

        # コールバック
        Route::get('/login/line/callback',
        [Controllers\Auth\LoginLineController::class, 'callback'])
        ->name('login.line.callback');

    # Googleログイン
    Route::get('/login/google',
    [Controllers\Auth\LoginGoogleController::class, 'index'])
    ->name('login.google');

        # コールバック
        Route::get('/login/google/callback',
        [Controllers\Auth\LoginGoogleController::class, 'callback'])
        ->name('login.google.callback');

    # Xログイン
    Route::get('/login/x/',
    [Controllers\Auth\LoginXController::class, 'index'])
    ->name('login.x');

        # コールバック
        Route::get('/login/x/callback',
        [Controllers\Auth\LoginXController::class, 'callback'])
        ->name('login.x.callback');

    # Yahooログイン
    Route::get('/login/yahoo',
    [Controllers\Auth\LoginYahooController::class, 'index'])
    ->name('login.yahoo');

        # コールバック
        Route::get('/login/yahoo/callback',
        [Controllers\Auth\LoginYahooController::class, 'callback'])
        ->name('login.yahoo.callback');
