<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin - 買取表カテゴリー
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/purchase/category',
    [Controllers\AdminPurchaseCategoryController::class, 'index'])
    ->name('admin.purchase.category');

    # 新規登録
    Route::get('/admin/purchase/category/create',
    [Controllers\AdminPurchaseCategoryController::class, 'create'])
    ->name('admin.purchase.category.create');

        # 登録
        Route::post('/admin/purchase/category/store',
        [Controllers\AdminPurchaseCategoryController::class, 'store'])
        ->name('admin.purchase.category.store');

    # 基本情報の編集
    Route::get('/admin/purchase/category/edit/{purchase_category}',
    [Controllers\AdminPurchaseCategoryController::class, 'edit'])
    ->name('admin.purchase.category.edit');

        # 基本情報の更新
        Route::patch('/admin/purchase/category/update/{purchase_category}',
        [Controllers\AdminPurchaseCategoryController::class, 'update'])
        ->name('admin.purchase.category.update');

    # 削除
    Route::delete('/admin/purchase/category/destroy/{purchase_category}',
    [Controllers\AdminPurchaseCategoryController::class, 'destroy'])
    ->name('admin.purchase.category.destroy');

    # 並び替え
    Route::get('/admin/purchase/category/change_order',
    [Controllers\AdminPurchaseCategoryController::class, 'change_order'])
    ->name('admin.purchase.category.change_order');

        # 並び替えの更新
        Route::patch('/admin/purchase/category/change_order/update',
        [Controllers\AdminPurchaseCategoryController::class, 'change_order_update'])
        ->name('admin.purchase.category.change_order.update');


});//end middleware
/*
|--------------------------------------------------------------------------
| Admin - 買取表 API
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧情報の発行API(admin_list)
    Route::post('admin/api/purchase/category',
    [Controllers\AdminApiPurchaseController::class, 'api_index'])
    ->name('admin.api.purchase.category');

});//end middleware

