<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| カテゴリー GachaCategory
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/category',
    [Controllers\AdminGachaCategoryController ::class, 'index'])
    ->name('admin.category');

    # 表示
    Route::get('/admin/category/show/{gacha_category}',
    [Controllers\AdminGachaCategoryController::class,'show'])
    ->name('admin.category.show');

    # 新規登録
    Route::get('/admin/category/create',
    [Controllers\AdminGachaCategoryController::class, 'create'])
    ->name('admin.category.create');

        # 登録
        Route::post('/admin/category/store',
        [Controllers\AdminGachaCategoryController::class, 'store'])
        ->name('admin.category.store');

    # 基本情報の編集
    Route::get('/admin/category/edit/{gacha_category}',
    [Controllers\AdminGachaCategoryController::class, 'edit'])
    ->name('admin.category.edit');

        # 基本情報の更新
        Route::patch('/admin/category/update/{gacha_category}',
        [Controllers\AdminGachaCategoryController::class, 'update'])
        ->name('admin.category.update');

    # 基本情報の編集(すべて)
    Route::get('/admin/category/all/edit',
    [Controllers\AdminGachaCategoryController::class, 'all_edit'])
    ->name('admin.category.all_edit');

        # 基本情報の更新(すべて)
        Route::patch('/admin/category/all/update',
        [Controllers\AdminGachaCategoryController::class, 'all_update'])
        ->name('admin.category.all_update');

    # 削除
    Route::delete('/admin/category/destroy/{gacha_category}',
    [Controllers\AdminGachaCategoryController::class, 'destroy'])
    ->name('admin.category.destroy');

    # 並び替え
    Route::get('/admin/category/change_order',
    [Controllers\AdminGachaCategoryController::class, 'change_order'])
    ->name('admin.category.change_order');

        # 並び替えの更新
        Route::patch('/admin/category/change_order/update',
        [Controllers\AdminGachaCategoryController::class, 'change_order_update'])
        ->name('admin.category.change_order.update');



    # 並び替えの更新
    Route::patch('/admin/category/change_order/update',
    [Controllers\AdminGachaCategoryController::class, 'change_order_update'])
    ->name('admin.category.change_order.update');

});//end middleware
