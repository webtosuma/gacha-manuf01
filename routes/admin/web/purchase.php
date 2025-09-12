<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin - 買取表
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/purchase',
    [Controllers\AdminPurchaseController ::class, 'index'])
    ->name('admin.purchase');

    # 新規登録
    Route::get('/admin/purchase/create',
    [Controllers\AdminPurchaseController::class, 'create'])
    ->name('admin.purchase.create');

        # 登録
        Route::post('/admin/purchase/store',
        [Controllers\AdminPurchaseController ::class, 'store'])
        ->name('admin.purchase.store');

    # 基本情報の編集
    Route::get('/admin/purchase/edit/{purchase}',
    [Controllers\AdminPurchaseController ::class, 'edit'])
    ->name('admin.purchase.edit');

        # 基本情報の更新
        Route::patch('/admin/purchase/update/{purchase}',
        [Controllers\AdminPurchaseController ::class, 'update'])
        ->name('admin.purchase.update');

    # 削除
    Route::delete('/admin/purchase/destroy/{purchase}',
    [Controllers\AdminPurchaseController ::class, 'destroy'])
    ->name('admin.purchase.destroy');



});//end middleware
/*
|--------------------------------------------------------------------------
| Admin - クーポン API
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 一覧情報の発行API(admin_list)
    Route::post('/admmin/api/purchase/',
    [Controllers\AdminApipurchaseController::class, 'index'])
    ->name('admin.api.purchase');


});//end middleware
