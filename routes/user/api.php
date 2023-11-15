<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;


/*
|--------------------------------------------------------------------------
| パスワード変更
|--------------------------------------------------------------------------
*/
    # パスワード変更API ステップ01(reset_pass_step01)
    Route::post('reset_pass_step01',
    [Controllers\UserController::class, 'reset_pass_step01'])
    ->name('reset_pass_step01');

    # パスワード変更API ステップ02(reset_pass_step02)
    Route::post('user/reset_pass_step02',
    [Controllers\UserController::class, 'reset_pass_step02'])
    ->name('reset_pass_step02');


/*
|--------------------------------------------------------------------------
| 取得した景品
|  UserPrizeController
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        # ユーザーの取得積み景品（ポイント交換・発送済みを除く）
        Route::post('user_prize',
        [Controllers\UserPrizeAPIController::class, 'index'])
        ->name('api_user_prize');

    });
