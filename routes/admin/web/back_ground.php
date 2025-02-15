<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| サイト背景の管理
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 編集
    Route::get('/admin/back_ground/',
    [Controllers\AdminBackGroundController::class, 'index'])
    ->name('admin.back_ground');

    # 編集
    Route::get('/admin/back_ground/edit',
    [Controllers\AdminBackGroundController::class, 'edit'])
    ->name('admin.back_ground.edit');

    # 更新
    Route::patch('/admin/back_ground/update',
    [Controllers\AdminBackGroundController::class, 'update'])
    ->name('admin.back_ground.update');


});//end middleware
