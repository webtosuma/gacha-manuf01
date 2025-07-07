<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/*
==========================================================================
 EC 買い物カート　ルーティング　web
==========================================================================
*/
Route::middleware(['auth'])->group(function () {

    # 一覧
    Route::get('store/keep',
    [Store\StoreKeepController::class,'index'])
    ->name('store.keep');


});




/* API */

    # カートへ入れる
    Route::post('store/keep/api/keep/{store_item}',
    [Store\StoreKeepApiController::class,'keep'])
    ->name('store.keep.api.keep');


Route::middleware(['auth'])->group(function () {

    # 一覧
    Route::post('store/keep/api',
    [Store\StoreKeepApiController::class,'index'])
    ->name('store.keep.api');

    # 購入数の変更
    Route::patch('store/keep/api/update/{store_keep}',
    [Store\StoreKeepApiController::class,'update'])
    ->name('store.keep.api.update');

    # 削除
    Route::delete('store/keep/api/destroy/{store_keep}',
    [Store\StoreKeepApiController::class,'destroy'])
    ->name('store.keep.api.destroy');

});
