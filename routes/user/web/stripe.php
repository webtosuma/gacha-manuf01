<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ポイント購入・履歴 (webhook)
|  PointSailController
|  PointHistoryController
|--------------------------------------------------------------------------
*/
# ポイント一覧
Route::get('point_sail',
// [Controllers\PointSailController::class, 'index'])
[Controllers\StripeController::class, 'index'])
->name('point_sail');


Route::middleware(['auth'])->group(function () {

    # 購入手続き
    Route::get('point_sail/payment/{point_sail}',
    [Controllers\StripeController::class, 'payment'])
    ->name('point_sail.payment');

    # 購入処理
    // Route::get('point_sail/payment_post/{stripe_id}',
    // [Controllers\StripeController::class, 'payment_post'])
    // ->name('point_sail.payment_post');

    # ポイント購入完了
    Route::get('point_sail/comp/{stripe_id}',
    [Controllers\StripeController::class, 'comp'])
    ->name('point_sail.comp');

    # ポイント購入履歴
    Route::get('point_history/{month?}',
    [Controllers\PointHistoryController::class, 'index'])
    ->name('point_history');

});
