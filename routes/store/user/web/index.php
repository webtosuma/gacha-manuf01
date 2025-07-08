<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/*
==========================================================================
 EC 商品一覧　ルーティング　web
==========================================================================
*/

    # 一覧
    Route::get('store',
    [Store\StoreController::class, 'index'])
    ->name('store');

    # 検索結果(商品一覧)
    Route::get('store/search',
    [Store\StoreController::class, 'search'])
    ->name('store.search');

    # 詳細表示
    Route::get('store/show/{code?}',
    [Store\StoreController::class, 'show'])
    ->name('store.show');



/* API */

    # 一覧API
    Route::post('store_item/api',
    [Store\StoreItemApiController::class,'index'])
    ->name('store_item.api');

    # 検索履歴取得
    Route::post('store_item/api/search_history',
    [Store\StoreItemApiController::class,'search_history'])
    ->name('store_item.api.search_history');

    # 検索履歴削除
    Route::delete('store_item/api/search_history/destory/{search_history}',
    [Store\StoreItemApiController::class,'search_history_destory'])
    ->name('store_item.api.search_history.destory');

