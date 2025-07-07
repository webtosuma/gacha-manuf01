<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/*
==========================================================================
 EC ポイント購入履歴　ルーティング　web
==========================================================================
*/
Route::middleware(['auth'])->group(function () {


    # 一覧
    Route::get('store/point_history',
    [Store\PointHistoryController::class, 'index'])
    ->name('store.point_history');


});
