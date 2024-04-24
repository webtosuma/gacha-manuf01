<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ガチャ
| GachaController
| GachaPlayController
| GachaSponsorAdController
|--------------------------------------------------------------------------
*/
    Route::middleware(['user_rank'])->group(function () {

        # ガチャカのテゴリー選択
        Route::get('/g/{category_code?}',
        [App\Http\Controllers\GachaController::class, 'index'])
        ->name('gacha_category');

        # ガチャカの詳細表示
        Route::get('/g/{category_code}/{gacha}/{key}',
        [App\Http\Controllers\GachaController::class, 'show'])
        ->name('gacha');

        # ガチャカの結果履歴(SNS等の公開用)
        Route::get('/result_history/{history_key}',
        [App\Http\Controllers\GachaController::class, 'result_history'])
        ->name('gacha.result_history');


        # ガチャ回数のカスタム
        Route::get('/g/custom_count/{category_code}/{gacha}/{key}',
        [App\Http\Controllers\GachaController::class, 'custom_count'])
        ->name('gacha.custom_count');

    });
    Route::middleware(['auth','user_rank'])->group(function () {


        # ガチャカで遊ぶ
        Route::post('/g/play/{category_code}/{gacha}/{key}',
        [App\Http\Controllers\GachaPlayController::class, 'play'])
        ->name('gacha.play');

        # ガチャカの結果表示
        Route::get('/result/{category_code}/{user_gacha_history}',
        [App\Http\Controllers\GachaController::class, 'result'])
        ->name('gacha.result');


        # 商品のポイント交換
        Route::patch('/g/exchange_points/{category_code}/{user_gacha_history}',
        [App\Http\Controllers\GachaController::class, 'exchange_points'])
        ->name('gacha.exchange_points');






    });


    /*  スポンサー広告ガチャ */
    Route::middleware(['auth','user_rank'])->group(function () {


        # スポンサーガチャカで遊ぶ
        Route::get('/gacha/sponsor_ad/movie/{user_gacha_history}',
        [App\Http\Controllers\GachaSponsorAdController::class, 'movie'])
        ->name('gacha.sponsor_ad.movie');


        # スポンサーサイトへリダイレクト
        Route::get('/gacha/sponsor_ad/redirect/{sponsor_ad}',
        [App\Http\Controllers\GachaSponsorAdController::class, 'redirect'])
        ->name('gacha.sponsor_ad.redirect');

    });

