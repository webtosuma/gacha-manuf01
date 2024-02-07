<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| ポイント購入・履歴  (Stripe・プロジェクト内で購入処理の実行)
|  PointSailController
|  PointHistoryController
|--------------------------------------------------------------------------
*/
# ポイント一覧
Route::get('point_sail',
[Controllers\StripeReactController::class, 'index'])
->name('point_sail');


Route::middleware(['auth'])->group(function () {

    # 購入手続き
    Route::get('point_sail/payment/{point_sail}',
    [Controllers\StripeReactController::class, 'payment'])
    ->name('point_sail.payment');

    # 購入処理
    // Route::post('point_sail/store/{point_sail}',
    // [Controllers\StripeReactController::class, 'store'])
    // ->name('point_sail.store');

    # ポイント購入完了
    Route::get('point_sail/comp/{stripe_id}',
    [Controllers\StripeReactController::class, 'comp'])
    ->name('point_sail.comp');

    # ポイントが不足しています
    Route::get('point_sail/shortage',
    [Controllers\StripeReactController::class, 'shortage'])
    ->name('point_sail.shortage');


    # ポイント購入履歴
    Route::get('point_history/{month?}',
    [Controllers\PointHistoryController::class, 'index'])
    ->name('point_history');

});

