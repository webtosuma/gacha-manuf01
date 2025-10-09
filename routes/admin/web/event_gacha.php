<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin イベントガチャ AdminEventGachaController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # ガチャ一覧
    Route::get('/event/g/{category_code?}',
    [Controllers\AdminEventGachaController ::class, 'index'])
    ->name('event.gacha');

    # 詳細
    Route::get('/event/g/{category_code}/{gacha}/{key}',
    [Controllers\AdminEventGachaController ::class, 'show'])
    ->name('event.gacha.show');

    # ガチャカで遊ぶ
    Route::post('/event/play/{category_code}/{gacha}/{key}',
    [App\Http\Controllers\AdminEventGachaController::class, 'play'])
    ->name('event.gacha.play');

    # ガチャの演出動画表示
    Route::get('/event/movie',
    [App\Http\Controllers\AdminEventGachaController::class, 'movie'])
    ->name('event.gacha.movie');

    # ガチャカの結果表示
    Route::get('/event/result/{category_code}/{user_gacha_history}',
    [App\Http\Controllers\AdminEventGachaController::class, 'result'])
    ->name('event.gacha.result');

    # 商品のポイント交換
    Route::patch('/event/g/exchange_points/{category_code}/{user_gacha_history}',
    [App\Http\Controllers\AdminEventGachaController::class, 'exchange_points'])
    ->name('event.gacha.exchange_points');




});//end middleware
