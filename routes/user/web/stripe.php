<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ポイント購入・履歴 (webhook)
|  StripSubscriptionController
|  PointHistoryController
|--------------------------------------------------------------------------
*/
    Route::middleware(['user_rank'])->group(function () {

        # ポイント一覧
        Route::get('point_sail',
        [Controllers\StripeController::class, 'index'])
        ->name('point_sail');


        # 決済完了ウェブホック //https://cardfesta.jp/stripe/webhook
        Route::post('stripe/webhook',
        [Controllers\StripeController::class, 'webhook']);


    });
    Route::middleware(['auth','user_rank'])->group(function () {

        # 購入手続き
        Route::get('point_sail/payment/{point_sail}',
        [Controllers\StripeController::class, 'payment'])
        ->name('point_sail.payment');

        # 購入処理
        // Route::get('point_sail/payment_post/{stripe_id}',
        // [Controllers\StripeController::class, 'payment_post'])
        // ->name('point_sail.payment_post');



        # ポイントが不足しています(StripeController)
        Route::get('point_sail/shortage',
        [Controllers\StripeController::class, 'shortage'])
        ->name('point_sail.shortage');


        # ポイント購入完了
        Route::get('point_sail/comp/{stripe_id}',
        [Controllers\StripeController::class, 'comp'])
        ->name('point_sail.comp');


        # ポイント購入履歴
        Route::get('point_history/{month?}',
        [Controllers\PointHistoryController::class, 'index'])
        ->name('point_history');

    });
