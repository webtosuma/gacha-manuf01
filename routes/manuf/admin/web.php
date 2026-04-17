<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
use App\Http\Controllers\Store;
/**
 * *********************************************************************
 *   Manufacturer:管理者(Admin) webルーティング
 * *********************************************************************
 */

    # ホーム(home)
    if( config('app.layout_app')=='store' ){

        Route::get('/admin',
        [Store\AdminHomeController::class,'index'])
        ->middleware('admin_auth')
        ->name('admin.home');

    }

    # ガチャタイトル
    include('web/gacha_title/index.php');//(基本情報)

        # タイトル商品
        include('web/gacha_title/title_prize.php');

        # 筺体
        include('web/gacha_title/machine.php');

            # テストプレイ

        # その他
        include('web/gacha_title/other.php');


    // # ストアー商品
    // include('web/store_item.php');

    // # ストアー商品・ガチャ商品
    // include('web/store_item_prize.php');

    // # ストアー商品・発送受付
    // include('web/store_shipped.php');


    // # EC売上レポート 一覧
    // include('web/sales_report.php');



//
