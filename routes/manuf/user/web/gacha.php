<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
==========================================================================
  Manufacturer:ルーティング　web.gacha
==========================================================================
*/

  # ガチャタイトルのカテゴリー選択
  Route::get('/m/{category_code?}',
  [Manuf\GachaTitleController::class, 'index'])
  ->name('manuf');

  # カテゴリー一覧
  Route::get('/g/{category_code?}',
  [Manuf\GachaTitleController::class, 'index'])
  ->name('gacha_category');

  # 検索結果
  Route::get('/m/search',
  [Manuf\GachaTitleController::class, 'search'])
  ->name('manuf.search');

  # ガチャタイトルの詳細表示
  Route::get('/m/{category_code}/{title_code}',
  [Manuf\GachaTitleController::class, 'show'])
  ->name('manuf.gacha_title');


/* ログイン必須 */
Route::middleware(['auth'])->group(function () {

  # ガチャの演出動画表示
  Route::get('movie/{item_code}/play',
  [Manuf\GachaTitleController::class, 'movie'])
  ->name('gacha.movie');

  # ガチャの結果表示
  Route::get('/result/{item_code}',
  [Manuf\GachaTitleController::class, 'result'])
  ->name('gacha.result');


});



