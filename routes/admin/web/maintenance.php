<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| メンテナンス表示の管理
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 編集
    Route::get('/admin/maintenance/',
    [Controllers\AdminMaintenanceController::class, 'index'])
    ->name('admin.maintenance');

    # 編集
    Route::get('/admin/maintenance/edit',
    [Controllers\AdminMaintenanceController::class, 'edit'])
    ->name('admin.maintenance.edit');

    # 更新
    Route::patch('/admin/maintenance/update',
    [Controllers\AdminMaintenanceController::class, 'update'])
    ->name('admin.maintenance.update');


});//end middleware
