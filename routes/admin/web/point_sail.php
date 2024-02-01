<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 販売ポイント
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/point_sail',
    [Controllers\AdminPointSailController::class, 'index'])
    ->name('admin.point_sail');

    # 表示
    Route::get('/admin/point_sail/show/{point_sail}',
    [Controllers\AdminPointSailController::class,'show'])
    ->name('admin.point_sail.show');

    # 新規登録
    Route::get('/admin/point_sail/create',
    [Controllers\AdminPointSailController::class, 'create'])
    ->name('admin.point_sail.create');

        # 登録
        Route::post('/admin/point_sail/store',
        [Controllers\AdminPointSailController::class, 'store'])
        ->name('admin.point_sail.store');

    # 基本情報の編集
    Route::get('/admin/point_sail/edit/{point_sail}',
    [Controllers\AdminPointSailController::class, 'edit'])
    ->name('admin.point_sail.edit');

        # 基本情報の更新
        Route::patch('/admin/point_sail/update/{point_sail}',
        [Controllers\AdminPointSailController::class, 'update'])
        ->name('admin.point_sail.update');

    # 削除
    Route::delete('/admin/point_sail/destroy/{point_sail}',
    [Controllers\AdminPointSailController::class, 'destroy'])
    ->name('admin.point_sail.destroy');


});//end middleware
