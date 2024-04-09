<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ポイント購入・履歴 (webhook)
|  StripSubscriptionController
|--------------------------------------------------------------------------
*/

    # ポイント一覧
    Route::get('point_sail/subscription',
    [Controllers\StripSubscriptionController::class, 'index'])
    ->name('point_sail.subscription');


    # 決済完了ウェブホック //https://cardfesta.jp/stripe/subscription/webhook
    Route::post('stripe/subscription/webhook',
    [Controllers\StripSubscriptionController::class, 'webhook']);


    Route::middleware(['auth','user_rank'])->group(function () {

        # サブスクプラン手続き
        Route::get('point_sail/subscription/payment',
        [Controllers\StripSubscriptionController::class, 'payment'])
        ->name('point_sail.subscription.payment');

        # サブスクプラン支払い完了
        Route::get('point_sail/subscription/comp/{stripe_id}',
        [Controllers\StripeController::class, 'comp'])
        ->name('point_sail.subscription.comp');

        # サブスクプランキャンセル
        Route::get('point_sail/subscription/cancel/{stripe_id}',
        [Controllers\StripeController::class, 'cancel'])
        ->name('point_sail.subscription.cancel');

        # サブスクプランキャンセルの取り消し
        Route::get('point_sail/subscription/not_cancel/{stripe_id}',
        [Controllers\StripeController::class, 'not_cancel'])
        ->name('point_sail.subscription.not_cancel');

    });
