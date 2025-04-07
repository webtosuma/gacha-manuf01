<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| PayPayでポイント購入
| PayPayController
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth','user_rank'])->group(function () {

        # 購入手続き
        Route::get('point_sail/paypay/payment/{point_sail}',
        [Controllers\PayPayController::class, 'payment'])
        ->name('point_sail.paypay.payment');

        # 決済完了ウェブホック //https://cardfesta.jp/paypay/webhook
        /* ! * App\Http\Middleware\VerifyCsrfTokenにて、CSRF除外処理を行うこと！ * ! */
        Route::post('paypay/webhook',
        [Controllers\StripeController::class, 'webhook']);
    });
