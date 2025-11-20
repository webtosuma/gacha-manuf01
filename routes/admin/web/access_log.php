<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
|  Admin アクセスログ
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/access_log',
    [Controllers\AdminAccessLogController::class,'index'])
    ->name('admin.access_log');


    # [API] 一覧
    Route::post('/admmin/api/access_log',
    [Controllers\AdminAccessLogController::class, 'api_list'])
    ->name('admin.api.access_log');


});//end middleware
