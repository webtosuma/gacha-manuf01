<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
==========================================================================
  Manufacturer:ルーティング　web.gacha
==========================================================================
*/
Route::middleware([ 
  'maintenance',//メンテナンス
])->group(function () {

  # ガチャタイトルのカテゴリー選択
  Route::get('/m',
  [Manuf\GachaTitleController::class, 'index'])
  ->name('manuf');

  # 検索結果
  Route::get('/m/search',
  [Manuf\GachaTitleController::class, 'search'])
  ->name('manuf.search');

  # ガチャタイトルの詳細表示
  Route::get('/m/{category_code}/{title_code}',
  [Manuf\GachaTitleController::class, 'show'])
  ->name('manuf.gacha_title'); 



});//end middleware


Route::middleware([
  'auth',       /* ログイン必須 */
  'maintenance',//メンテナンス
])->group(function () {


  # ガチャの演出動画表示
  Route::get('movie/{item_code}/play',
  [Manuf\GachaTitleController::class, 'movie'])
  ->name('gacha.movie');

  # ガチャの結果表示
  Route::get('/result/{item_code}',
  [Manuf\GachaTitleController::class, 'result'])
  ->name('gacha.result');


});



