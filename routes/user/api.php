<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;


/*
|--------------------------------------------------------------------------
| パスワード変更
|--------------------------------------------------------------------------
*/
    # パスワード変更API ステップ01(reset_pass_step01)
    Route::post('reset_pass_step01/api',
    [Controllers\UserController::class, 'reset_pass_step01'])
    ->name('reset_pass_step01');

    # パスワード変更API ステップ02(reset_pass_step02)
    Route::post('user/reset_pass_step02/api',
    [Controllers\UserController::class, 'reset_pass_step02'])
    ->name('reset_pass_step02');


/*
|--------------------------------------------------------------------------
| 取得した景品
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        # ユーザーの取得積み景品
        //（ポイント交換・発送済みを除く）
        Route::post('user_prize/api',
        [Controllers\UserPrizeAPIController::class, 'index'])
        ->name('api_user_prize');
    });


/*
|--------------------------------------------------------------------------
| ガチャ履歴
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    # ユーザーのガチャ履歴に紐づいた、ユーザーの取得積み景品
    Route::post('use_gacha_history/{user_gacha_history}/api',
    [Controllers\UserGachaHistoryApiContloller::class, 'show'])
    ->name('use_gacha_history.show');

});



/*
|--------------------------------------------------------------------------
| ユーザーアドレス
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth'])->group(function () {

    Route::post('use_address/store/api',
    [Controllers\UserAddressApiController::class, 'store'])
    ->name('api.use_address.store');

// });

