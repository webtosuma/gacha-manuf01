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

        # checkout
        Route::get('point_sail/subscription/checkout/{subscription_id}',
        [Controllers\StripSubscriptionController::class, 'checkout'])
        ->name('point_sail.subscription.checkout');


        # サブスクプラン支払い完了
        Route::get('point_sail/subscription/comp/{stripe_id}',
        [Controllers\StripSubscriptionController::class, 'comp'])
        ->name('point_sail.subscription.comp');

    });
