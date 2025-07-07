<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/**
 * *********************************************************************
 *   管理者(Admin) ストアー商品 ルーティング
 * *********************************************************************
 */
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/store_item',
    [Store\AdminStoreItemController::class,'index'])
    ->name('admin.store_item');

    # 新規作成
    Route::get('/admin/store_item/create/',
    [Store\AdminStoreItemController::class, 'create'])
    ->name('admin.store_item.create');

        # 登録
        Route::post('/admin/store_item/store',
        [Store\AdminStoreItemController::class, 'store'])
        ->name('admin.store_item.store');

    # 編集
    Route::get('/admin/store_item/edit/{store_item}',
    [Store\AdminStoreItemController::class, 'edit'])
    ->name('admin.store_item.edit');

        # 更新
        Route::patch('/admin/store_item/update/{store_item}',
        [Store\AdminStoreItemController::class, 'update'])
        ->name('admin.store_item.update');


    # 動画の編集
    Route::get('/admin/store_item/movie/edit/{store_item}',
    [Store\AdminStoreItemController::class, 'movie_edit'])
    ->name('admin.store_item.movie.edit');

        # 動画の更新
        Route::patch('/admin/store_item/movie/update/{store_item}',
        [Store\AdminStoreItemController::class, 'movie_update'])
        ->name('admin.store_item.movie.update');


});//end middleware
