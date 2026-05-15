<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
==========================================================================
  Manufacturer: ガチャタイトル　購入 ルーティング　
==========================================================================
*/

# 決済完了ウェブホック StripeController
/* ! * App\Http\Middleware\VerifyCsrfTokenにて、CSRF除外処理を行うこと！ * ! */
Route::post('stripe/webhook',
[Manuf\StripeController::class, 'webhook']);


Route::middleware(['auth'])->group(function () {

  # ガチャタイトルの筐体 購入[決済チェックアウト] StripeController
  Route::post('m/purchase/checkout',
  [Manuf\StripeController::class, 'checkout'])
  ->name('manuf.gacha_title.purchase.checkout');

  
  
  # ガチャタイトルの筐体 購入[入力]
  Route::get('m/purchase/appliy',
  [Manuf\PurchaseController::class, 'appliy'])
  ->name('manuf.gacha_title.purchase.appliy');

  # ガチャタイトルの筐体  購入[確認]( 購入履歴作成 )
  Route::post('m/purchase/confirm',
  [Manuf\PurchaseController::class, 'confirm'])
  ->name('manuf.gacha_title.purchase.confirm');

  # ガチャタイトルの筐体  購入[キャンセル]( 戻る用 )
  Route::get('m/purchase/cancel/{code}',
  [Manuf\PurchaseController::class, 'cancel'])
  ->name('manuf.gacha_title.purchase.cancel');

  # ガチャタイトルの筐体 購入[完了]
  Route::get('m/purchase/comp/{code}',
  [Manuf\PurchaseController::class, 'comp'])
  ->name('manuf.gacha_title.purchase.comp');


});
