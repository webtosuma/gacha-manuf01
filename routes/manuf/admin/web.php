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
    Route::get('/admin',
    [Manuf\AdminHomeController::class,'index'])
    ->middleware('admin_auth')
    ->name('admin.home');


    # ガチャタイトル

        # 筺体
        include('web/gacha_title/machine.php');

        # タイトル商品
        include('web/gacha_title/title_prize.php');


        # テストプレイ

        # その他
        include('web/gacha_title/other.php');

        include('web/gacha_title/index.php');//(基本情報)


    // # ストアー商品
    // include('web/store_item.php');

    // # ストアー商品・ガチャ商品
    // include('web/store_item_prize.php');

    // # ストアー商品・発送受付
    // include('web/store_shipped.php');


    // # EC売上レポート 一覧
    // include('web/sales_report.php');



//
