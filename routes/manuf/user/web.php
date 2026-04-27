<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;

/*
==========================================================================
  Manufacturer:ルーティング　web
==========================================================================
*/

Route::middleware([ /* ミドルウェアー */
    'maintenance',           //メンテナンス
    'user_session_validate', //1アカウント1ログイン(セッションIDチェック)
])->group(function () {

  # トップページ(製造業者用のみのとき)
  Route::get('/',
  [Manuf\GachaTitleController::class, 'index'])
  ->name('home');

  # ガチャ
  include('web/gacha.php');

    
    // # 商品一覧
    // include('web/index.php');


    // # お知らせ(news)
    // Route::get('store/infomation',
    // [App\Http\Controllers\InfomationController::class,'store_index'])
    // ->name('store.infomation');


});//end middleware
