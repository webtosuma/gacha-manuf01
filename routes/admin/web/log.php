<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
|  Admin 操作履歴　
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/log',
    [Controllers\AdminLogController::class,'index'])
    ->name('admin.log');


    # [API] 一覧
    Route::post('/admmin/api/log',
    [Controllers\AdminLogController::class, 'api_list'])
    ->name('api.admin.log');


});//end middleware
