<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| fincode FincodeController
|--------------------------------------------------------------------------
*/

    # ポイント一覧(戻る用)
    Route::post('point_sail/fc/post', function(){
        return redirect()->route('point_sail');
    })->name('point_sail.fc.post');


    Route::middleware(['auth','user_rank'])->group(function () {

        # 購入手続き
        Route::get('point_sail/fc/payment/{point_sail}',
        [Controllers\FincodeController::class, 'payment'])
        ->name('point_sail.fc.payment');

    });


    # ポイント購入完了=>ポイント付与処理
    /* ! * App\Http\Middleware\VerifyCsrfTokenにて、CSRF除外処理を行うこと！ * ! */
    Route::post('point_sail/fc/callback',
    [Controllers\FincodeController::class, 'callback'])
    ->name('point_sail.fc.callback');
