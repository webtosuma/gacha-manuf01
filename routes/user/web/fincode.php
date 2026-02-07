<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| fincode FincodeController
|--------------------------------------------------------------------------
*/

    # ポイント一覧
    // Route::get('point_sail/fc',
    // [Controllers\FincodeController::class, 'index'])
    // ->name('point_sail');

    Route::post('point_sail/fc/post', function(){
        return redirect()->route('point_sail');
    })->name('point_sail.fc.post');


    // # 決済完了ウェブホック //https://cardfesta.jp/fincode/webhook
    // Route::post('fincode/webhook',
    // [Controllers\FincodeController::class, 'webhook'])
    // ->name('fincode.webhook');


    # ポイント購入完了受け取り
    Route::post('point_sail/comp_post/{stripe_id}',
    /* ! * App\Http\Middleware\VerifyCsrfTokenにて、CSRF除外処理を行うこと！ * ! */
    [Controllers\FincodeController::class, 'comp_post'])
    ->name('point_sail.fc.comp_post');

    # ポイント購入完了
    // Route::get('point_sail/comp/{stripe_id}',
    // [Controllers\FincodeController::class, 'comp'])
    // ->name('point_sail.comp');



// });
Route::middleware(['auth','user_rank'])->group(function () {

    # 購入手続き
    Route::get('point_sail/fc/payment/{point_sail}',
    [Controllers\FincodeController::class, 'payment'])
    ->name('point_sail.fc.payment');


    // # ポイントが不足しています(FincodeController)
    // Route::get('point_sail/shortage',
    // [Controllers\FincodeController::class, 'shortage'])
    // ->name('point_sail.shortage');

    // # ポイント購入履歴
    // Route::get('point_history/{month?}',
    // [Controllers\PointHistoryController::class, 'index'])
    // ->name('point_history');

});


