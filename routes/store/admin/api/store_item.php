<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/**
 * *********************************************************************
 *   管理者(Admin) API ストアー商品 ルーティング
 * *********************************************************************
 */
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::post('/admin/api/store_item',
    [Store\AdminApiStoreItemController::class,'index'])
    ->name('admin.api.store_item');


    # 更新
    Route::patch('admin/api/store_item/update',
    [Store\AdminApiStoreItemController::class, 'update'])
    ->name('admin.api.store_item.update');

    // # チケット交換商品の削除
    // Route::delete('admin/api/ticket_store/destroy/{store?}',
    // [Controllers\AdminApiTicketStoreController::class, 'destroy'])
    // ->name('admin.api.ticket_store.destroy');



});//end middleware
