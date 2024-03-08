<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| チケット交換商品 AdminTicketStoreController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 一覧
    Route::get('/admin/ticket_store/{category_id?}',
    [Controllers\AdminTicketStoreController ::class, 'index'])
    ->name('admin.ticket_store');

    // # 新規作成
    // Route::get('/admin/ticket_store/create',
    // [Controllers\AdminTicketStoreController ::class, 'create'])
    // ->name('admin.ticket_store.create');

    // # 登録
    // Route::post('/admin/ticket_store/store',
    // [Controllers\AdminTicketStoreController ::class, 'store'])
    // ->name('admin.ticket_store.store');

    // # 編集
    // Route::get('/admin/ticket_store/edit/{prize?}',//?:componentで利用
    // [Controllers\AdminTicketStoreController ::class, 'edit'])
    // ->name('admin.ticket_store.edit');

    // # 更新
    // Route::patch('/admin/ticket_store/update/{prize}',
    // [Controllers\AdminTicketStoreController ::class, 'update'])
    // ->name('admin.ticket_store.update');


    // # CSVファイルのダウンロード
    // Route::post('/admin/ticket_store/download/csv',
    // [Controllers\AdminTicketStoreController ::class, 'download_csv'])
    // ->name('admin.ticket_store.download_csv');

});
