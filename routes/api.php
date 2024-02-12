<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// # 決済完了ウェブホック //https://cardfesta.jp/point_sail/api/payment/stripe/webhook
// Route::post('point_sail/payment/stripe/webhook',
// [Controllers\StripeController::class, 'webhook'])
// ->name('point_sail.payment.webhook');


