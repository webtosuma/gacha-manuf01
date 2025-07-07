<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/**
 * *********************************************************************
 *   管理者(Admin) ストアー商品 ガチャ商品の登録 ルーティング
 * *********************************************************************
 */
Route::middleware(['admin_auth'])->group(function () {

    # ガチャ用商品を追加
    Route::get('/admin/store_item/prize/create',
    [Store\AdminStoreItemPrizeController::class, 'create'])
    ->name('admin.store_item.prize.create');

        # ガチャ用商品の登録
        Route::post('/admin/store_item/prize/store',
        [Store\AdminStoreItemPrizeController::class, 'store'])
        ->name('admin.store_item.prize.store');

});//end middleware


/**
 * *********************************************************************
 *   管理者(Admin) API ストアー商品 ガチャ商品の登録 ルーティング
 * *********************************************************************
 */
Route::middleware(['admin_auth'])->group(function () {

    # 商品一覧情報の取得
    Route::post('admin/api/store_item/prize',
    [Store\AdminStoreItemPrizeController::class, 'api_index'])
    ->name('admin.api.store_item.prize');

});
