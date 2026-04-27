<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
==========================================================================
  Manufacturer:ルーティング　web.gacha
==========================================================================
*/

    // Route::middleware(['user_rank'])->group(function () {

        // # API ガチャ一覧取得
        // Route::post('/gacha/api/list',
        // [App\Http\Controllers\GachaApiController::class, 'list'])
        // ->name('gacha.api.list');

        # ガチャタイトルのカテゴリー選択
        Route::get('/m/{category_code?}',
        [Manuf\GachaTitleController::class, 'index'])
        ->name('manuf');

        # カテゴリー一覧
        Route::get('/g/{category_code?}',
        [Manuf\GachaTitleController::class, 'index'])
        ->name('gacha_category');

        # 検索結果
        Route::get('/m/search',
        [Manuf\GachaTitleController::class, 'search'])
        ->name('manuf.search');

        # ガチャタイトルの詳細表示
        Route::get('/m/{category_code}/{title_code}',
        [Manuf\GachaTitleController::class, 'show'])
        ->name('manuf.gacha_title');



        # ガチャタイトルの筐体 購入[入力]
        Route::get('m/{category_code}/{title_code}/machin/purchase/appliy',
        [Manuf\GachaTitlePurchaseController::class, 'appliy'])
        ->name('manuf.gacha_title.purchase.appliy');

        # ガチャタイトルの筐体 購入[確認]
        Route::post('m/{category_code}/{title_code}/machin/purchase/confirm',
        [Manuf\GachaTitlePurchaseController::class, 'confirm'])
        ->name('manuf.gacha_title.purchase.confirm');

          # ガチャタイトルの筐体 購入[決済チェックアウト]
          Route::post('m/{category_code}/{title_code}/machin/purchase/checkout',
          [Manuf\GachaTitlePurchaseController::class, 'checkout'])
          ->name('manuf.gacha_title.purchase.checkout');
        
        # ガチャタイトルの筐体 購入[完了]
        Route::get('m/{category_code}/{title_code}/machin/purchase/comp',
        [Manuf\GachaTitlePurchaseController::class, 'comp'])
        ->name('manuf.gacha_title.purchase.comp');
        


        // # ガチャの結果履歴(SNS等の公開用)
        // Route::get('/result_history/{history_key}',
        // [App\Http\Controllers\GachaController::class, 'result_history'])
        // ->name('gacha.result_history');

        // # ガチャ回数のカスタム
        // Route::get('/g/custom_count/{category_code}/{gacha}/{key}',
        // [App\Http\Controllers\GachaController::class, 'custom_count'])
        // ->name('gacha.custom_count');

    // });
    // Route::middleware(['auth'])->group(function () {


    //     # ガチャで遊ぶ
    //     Route::post('/g/play/{category_code}/{gacha}/{key}',
    //     [App\Http\Controllers\GachaPlayController::class, 'play'])
    //     ->name('gacha.play');


    //     # ガチャの演出動画表示
    //     Route::get('/movie/{user_gacha_history}',
    //     [App\Http\Controllers\GachaController::class, 'movie'])
    //     ->name('gacha.movie');


    //     # ガチャの結果表示
    //     Route::get('/result/{category_code}/{user_gacha_history}',
    //     [App\Http\Controllers\GachaController::class, 'result'])
    //     ->name('gacha.result');


    //     # 商品のポイント交換
    //     Route::patch('/g/exchange_points/{category_code}/{user_gacha_history}',
    //     [App\Http\Controllers\GachaController::class, 'exchange_points'])
    //     ->name('gacha.exchange_points');



    // });



