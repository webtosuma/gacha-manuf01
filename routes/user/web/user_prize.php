<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 取得した商品
|  UserPrizeController
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','user_rank'])->group(function () {

    # 商品一覧
    Route::get('user_prize',
    [Controllers\UserPrizeController::class, 'index'])
    ->name('user_prize');

    // # 商品のポイント交換
    // Route::patch('user_prize/exchange_points',
    // [Controllers\UserPrizeController::class, 'exchange_points'])
    // ->name('user_prize.exchange_points');


    # 商品のポイント交換
    Route::get('user_prize/exchange_points',
    [Controllers\UserPrizeController::class, 'exchange_points'])
    ->name('user_prize.exchange_points');

    # API商品のポイント交換
    Route::post('user_prize/exchange_points',
    [Controllers\UserPrizeApiController::class, 'exchange_points'])
    ->name('api.user_prize.exchange_points');
});
