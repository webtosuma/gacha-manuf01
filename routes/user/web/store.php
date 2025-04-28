<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 商品ストアー
|  StoreController
|--------------------------------------------------------------------------
*/
    Route::middleware(['user_rank'])->group(function () {

        # 一覧
        Route::get('store',
        [Controllers\StoreController::class, 'index'])
        ->name('store');

        # 検索結果(商品一覧)
        Route::get('store/search',
        [Controllers\StoreController::class, 'search'])
        ->name('store.search');

        # 詳細表示
        Route::get('store/show/{store?}',
        [Controllers\StoreController::class, 'show'])
        ->name('store.show');

    });
    Route::middleware(['auth','user_rank'])->group(function () {

        # チケット交換処理
        Route::post('store/post/{store}',
        [Controllers\StoreController::class, 'post'])
        ->name('store.post');

        # 交換完了完了
        Route::get('store/comp/{ticket_history}/{key}',
        [Controllers\StoreController::class, 'comp'])
        ->name('store.comp');

    });
