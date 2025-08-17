<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/**
 * *********************************************************************
 *   管理者(Admin) ポイント売上レポート
 * *********************************************************************
 */
Route::middleware(['admin_auth'])->group(function () {


    Route::get('/admin/point_sales_report',
    [Controllers\AdminPointSalesReportController::class,'index'])
    ->name('admin.point_sales_report');

        # 日別レポート
        Route::get('/admin/point_sales_report/daily/{date_format}',
        [Controllers\AdminPointSalesReportDailyController ::class, 'index'])
        ->name('admin.point_sales_report.daily');



    # API一覧
    Route::post('/admin/api/point_sales_report',
    [Controllers\AdminPointSalesReportController::class,'api_index'])
    ->name('admin.api.point_sales_report');

        # API 日別レポート
        Route::post('/admin/api/point_sales_report/daily/{date_format}',
        [Controllers\AdminPointSalesReportDailyController ::class, 'api_index'])
        ->name('admin.api.point_sales_report.daily');

    # API 顧客一覧
    Route::post('/admin/api/point_sales_report/visiters',
    [Controllers\AdminPointSalesReportVisitersController::class,'api_index'])
    ->name('admin.api.point_sales_report.visiters');

    # API 商品一覧
    Route::post('/admin/api/point_sales_report/products',
    [Controllers\AdminPointSalesReportProductsController::class,'api_index'])
    ->name('admin.api.point_sales_report.products');


});//end middleware

