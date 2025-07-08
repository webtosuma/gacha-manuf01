<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/**
 * *********************************************************************
 *   管理者(Admin) webルーティング
 * *********************************************************************
 */

    # ホーム(home)
    if( config('app.layout_app')=='store' ){

        Route::get('/admin',
        [Store\AdminHomeController::class,'index'])
        ->middleware('admin_auth')
        ->name('admin.home');

    }


    # ストアー商品
    include('web/store_item.php');

    # ストアー商品・ガチャ商品
    include('web/store_item_prize.php');

    # ストアー商品・発送受付
    include('web/store_shipped.php');


    # EC売上レポート 一覧
    Route::get('/admin/store/sales_report',
    [Store\AdminStoreSalesReportController::class,'index'])
    ->name('admin.store.sales_report');

    # EC販売商品レポート 一覧
    Route::get('/admin/store/prodact_report',
    [Store\AdminStoreProdactReportController::class,'index'])
    ->name('admin.store.prodact_report');
