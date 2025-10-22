<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ポイント購入・履歴 (webhook)
|  StripSubscriptionController
|  PointHistoryController
|
|  2026/06/18 url表示をpoint_sail->purchase_pointに変更
|--------------------------------------------------------------------------
*/
    Route::middleware(['user_rank'])->group(function () {

        # ポイント一覧
        Route::get('purchase_point/',
        [Controllers\StripeController::class, 'index'])
        ->middleware(['check.user.age'])//誕生日入力・年齢チェク
        ->name('point_sail');


        # 決済完了ウェブホック //https://cardfesta.jp/stripe/webhook
        /* ! * App\Http\Middleware\VerifyCsrfTokenにて、CSRF除外処理を行うこと！ * ! */
        Route::post('stripe/webhook',
        [Controllers\StripeController::class, 'webhook']);


    });
    Route::middleware(['auth','user_rank'])->group(function () {

        # 購入手続き
        Route::get('purchase_point/payment/{point_sail}',
        [Controllers\StripeController::class, 'payment'])
        ->name('point_sail.payment');

        # ポイントが不足しています(StripeController)
        Route::get('purchase_point/shortage',
        [Controllers\StripeController::class, 'shortage'])
        ->name('point_sail.shortage');


        # ポイント購入完了
        Route::get('purchase_point/comp/{stripe_id}',
        [Controllers\StripeController::class, 'comp'])
        ->name('point_sail.comp');


        # カスタマーポータル
        Route::get('point_history/customer_portal',
        [Controllers\StripeController::class, 'customer_portal'])
        ->name('point_sail.customer_portal');

        # ポイント購入履歴
        Route::get('point_history/{month?}',
        [Controllers\PointHistoryController::class, 'index'])
        ->name('point_history');

    });
