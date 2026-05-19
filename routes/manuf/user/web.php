<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;

/*
==========================================================================
  Manufacturer:ルーティング　web
==========================================================================
*/

// Route::middleware([ 
//   'maintenance',           //メンテナンス
// ])->group(function () {

  # トップページ(製造業者用のみのとき)
  Route::get('/',
  [Manuf\GachaTitleController::class, 'index'])
  ->middleware(['maintenance'])
  ->name('home');


  # ガチャ購入
  include('web/purchase.php');

  # ガチャ
  include('web/gacha.php');

  # 発送申請履歴
  include('web/shipped_history02.php');


// });//end middleware
