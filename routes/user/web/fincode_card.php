<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| fincode クレジットカード (fincode_card)  FincodeCardController
|--------------------------------------------------------------------------
*/
    # ポイント一覧
    Route::get('point_sail',
    [Controllers\FincodeCardController::class, 'index'])
    ->name('point_sail');

    Route::post('point_sail/post', function(){
        return redirect()->route('point_sail');
    })->name('point_sail.post');


    # 決済失敗(post)
    Route::post('point_sail/failuret', function(){
        return '決済に失敗しました。';
    })->name('point_sail.failure');


    # リダイレクト
    Route::post('point_sail/payment/{point_sail}', function($point_sail){
        return redirect()->route('point_sail.payment', $point_sail);
    })->name('point_sail.payment.post');


    Route::middleware(['auth','user_rank'])->group(function () {

        # 購入手続き:支払いクレジットカード選択
        Route::get('point_sail/payment/{point_sail}',
        [Controllers\FincodeCardController::class, 'payment'])
        ->name('point_sail.payment');

        # ポイントが不足しています(FincodeController)
        Route::get('point_sail/shortage',
        [Controllers\FincodeCardController::class, 'shortage'])
        ->name('point_sail.shortage');


        # クレジットカード登録(リダイレクト)
        Route::get('point_sail/create_card/{point_sail}',
        [Controllers\FincodeCardController::class, 'create_card'])
        ->name('point_sail.create_card');

        # 購入処理
        Route::post('point_sail/payment/{point_sail}/process',
        [Controllers\FincodeCardController::class, 'process'])
        ->name('point_sail.process');

        # 購入完了
        Route::get('point_sail/comp/{stripe_id}',
        [Controllers\FincodeCardController::class, 'comp'])
        ->name('point_sail.comp');


        # ポイント購入履歴
        Route::get('point_history/{month?}',
        [Controllers\PointHistoryController::class, 'index'])
        ->name('point_history');

    });
