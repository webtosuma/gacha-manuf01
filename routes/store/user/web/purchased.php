<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/*
==========================================================================
 EC 購入商品一覧　ルーティング　web
==========================================================================
*/
Route::middleware(['auth'])->group(function () {


    # 一覧
    Route:: get('store/purchased',
    [Store\PurchasedController::class,'index'])
    ->name('store.purchased');


    # API 一覧
    Route::post('store/api/purchased',
    [Store\PurchasedController::class,'api_index'])
    ->name('store.api.purchased');

});
