<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| サブスクプラン購入 (checkout)
|  StripSubscriptionController
|--------------------------------------------------------------------------
*/

    # サブスクリプションページ
    Route::get('point_sail/subscription',
    [Controllers\StripSubscriptionController::class, 'index'])
    ->name('point_sail.subscription');


    # サブスク決済完了ウェブホック //https://cardfesta.jp/stripe/subscription/webhook
    /* ! * App\Http\Middleware\VerifyCsrfTokenにて、CSRF除外処理を行うこと！ * ! */
    Route::post('stripe/subscription/webhook',
    [Controllers\StripSubscriptionController::class, 'webhook']);


    Route::middleware(['auth','user_rank'])->group(function () {

        # サブスクプラン手続き
        Route::get('point_sail/subscription/payment/{subscription_id}',
        [Controllers\StripSubscriptionController::class, 'payment'])
        ->name('point_sail.subscription.payment');


        # サブスクプラン支払い完了
        Route::get('point_sail/subscription/comp/{stripe_id}',
        [Controllers\StripSubscriptionController::class, 'comp'])
        ->name('point_sail.subscription.comp');

        # サブスクプラン解約
        Route::get('point_sail/subscription/destroy/{stripe_id}',
        [Controllers\StripSubscriptionController::class, 'destroy'])
        ->name('point_sail.subscription.destroy');

        // # サブスクプランキャンセルの取り消し
        // Route::get('point_sail/subscription/not_cancel/{stripe_id}',
        // [Controllers\StripSubscriptionController::class, 'not_cancel'])
        // ->name('point_sail.subscription.not_cancel');

    });
