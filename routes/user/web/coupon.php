<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| クーポン
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','user_rank'])->group(function () {

    # 一覧
    Route::get('/coupon',
    [Controllers\CouponController ::class, 'index'])
    ->name('coupon');

    # 履歴
    Route::get('/coupon/history',
    [Controllers\CouponController ::class, 'history'])
    ->name('coupon.history');

    # 詳細
    Route::get('/coupon/show',
    [Controllers\CouponController ::class, 'show'])
    ->name('coupon.show');

    # クーポン利用
    Route::patch('/coupon/used',
    [Controllers\CouponController ::class, 'used'])
    ->name('coupon.used');

    # 利用完了
    Route::get('/coupon/comp/{coupon_history}',
    [Controllers\CouponController ::class, 'comp'])
    ->name('coupon.comp');
});
