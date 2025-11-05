<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/**
 * *********************************************************************
 *   管理者(Admin) ストアー商品・発送受付 ルーティング
 * *********************************************************************
 */
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/store/shipped',
    [Store\AdminStoreShippedController::class,'index'])
    ->name('admin.store.shipped');

        # 詳細
        Route::get('/admin/store/shipped/show/{store_history}',
        [Store\AdminStoreShippedController::class, 'show'])
        ->name('admin.store.shipped.show');

        # 発送処理
        Route::patch('/admin/store/shipped/update/{store_history?}',
        [Store\AdminStoreShippedController ::class, 'update'])
        ->name('admin.store.shipped.waiting.update');

        # CSVファイルのダウンロード
        Route::get('/admin/store/shipped/waiting/dl_csv',
        [Store\AdminStoreShippedController::class, 'dl_csv'])
        ->name('admin.store.shipped.waiting.dl_csv');



    # API 一覧 (AdminApiStoreShippedController)
    Route::post('admin/api/store/shipped',
    [Store\AdminApiStoreShippedController::class,'index'])
    ->name('admin.api.store.shipped');



});//end middleware
