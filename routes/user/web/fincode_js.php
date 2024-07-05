<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| fincode_js FincodeJSController
|--------------------------------------------------------------------------
*/
        Route::get('point_sail/fc/test', function(){
            return view('point_sail..fincode_js.test');
        });


    # ポイント一覧
    Route::get('point_sail/fc',
    [Controllers\FincodeJsController::class, 'index'])
    ->name('point_sail');

    Route::post('point_sail/fc/post', function(){
        return redirect()->route('point_sail');
    })->name('point_sail.post');


    # 決済失敗(post)
    Route::post('point_sail/fc/failuret', function(){
        return '決済に失敗しました。';
    })->name('point_sail.failure');


    Route::middleware(['auth','user_rank'])->group(function () {


        # ポイントが不足しています(FincodeController)
        Route::get('point_sail/shortage',
        [Controllers\FincodeJsController::class, 'shortage'])
        ->name('point_sail.shortage');


        # 購入手続き
        Route::get('point_sail/payment/{point_sail}',
        [Controllers\FincodeJsController::class, 'payment'])
        ->name('point_sail.payment');

        # クレジットカード登録(リダイレクト)
        Route::get('point_sail/create_card/{point_sail}',
        [Controllers\FincodeJsController::class, 'create_card'])
        ->name('point_sail.create_card');


        # ポイント購入履歴
        Route::get('point_history/{month?}',
        [Controllers\PointHistoryController::class, 'index'])
        ->name('point_history');

    });
