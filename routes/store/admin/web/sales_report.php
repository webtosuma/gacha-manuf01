<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/**
 * *********************************************************************
 *   管理者(Admin) EC売上レポート 一覧 ルーティング
 * *********************************************************************
 */
Route::middleware(['admin_auth'])->group(function () {


    Route::get('/admin/store/sales_report',
    [Store\AdminStoreSalesReportController::class,'index'])
    ->name('admin.store.sales_report');

    # 日別レポート
    Route::get('/admin/store/sales_report/daily/{date_format}',
    [Controllers\AdminStoreSalesReportDailyController ::class, 'index'])
    ->name('admin.store.sales_report.daily');



    # API一覧
    Route::post('/admin/api/store/sales_report',
    [Store\AdminStoreSalesReportController::class,'api_index'])
    ->name('admin.api.store.sales_report');

    # API 日別レポート
    Route::get('/admin/api/store/sales_report/daily/{date_format}',
    [Controllers\AdminStoreSalesReportDailyController ::class, 'api_index'])
    ->name('admin.api.store.sales_report.daily');

});//end middleware
