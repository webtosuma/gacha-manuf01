<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ガチャ(遊ぶ) AdminGachaPlayController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # ガチャカで遊ぶ
    Route::post('/admin/gacha/play/{category_code}/{gacha}/{key}',
    [App\Http\Controllers\AdminGachaPlayController::class, 'play'])
    ->name('admin.gacha.play');

    # ガチャの演出動画表示
    Route::get('/admin/gacha/movie',
    [App\Http\Controllers\AdminGachaPlayController::class, 'movie'])
    ->name('admin.gacha.movie');

    # ガチャカの結果表示
    Route::get('/admin/gacha/result/{category_code}/{user_gacha_history}',
    [App\Http\Controllers\AdminGachaPlayController::class, 'result'])
    ->name('admin.gacha.result');


    # 商品のポイント交換
    Route::patch('/admin/gacha/exchange_points/{category_code}/{user_gacha_history}',
    [App\Http\Controllers\AdminGachaPlayController::class, 'exchange_points'])
    ->name('admin.gacha.exchange_points');




    # スポンサーガチャカで遊ぶ
    Route::get('/admin/gacha/sponsor_ad/movie/{user_gacha_history}',
    [App\Http\Controllers\AdminGachaSponsorAdController::class, 'movie'])
    ->name('admin.gacha.sponsor_ad.movie');


});//end middleware
