<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/*
==========================================================================
 EC 発送履歴　ルーティング　web
==========================================================================
*/
Route::middleware(['auth'])->group(function () {


    # 一覧
    Route::get('store/shipped',
    [Store\ShippedController::class, 'index'])
    ->name('store.shipped');

    # 詳細
    Route::get('store/shipped/show/{store_history_code}',
    [Store\ShippedController::class, 'show'])
    ->name('store.shipped.show');


    # API 一覧
    Route::post('store/api/shipped',
    [Store\ShippedController::class,'api_index'])
    ->name('store.api.shipped');
});
