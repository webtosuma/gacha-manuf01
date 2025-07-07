<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/*
==========================================================================
 EC 購入手続き　ルーティング　web
==========================================================================
*/
Route::middleware(['auth'])->group(function () {

    # 手続き
    Route::post('store/purchase/appli',
    [Store\PurchaseController::class,'appli'])
    ->name('store.purchase.appli');

    // # 確認(決済履歴の保存)
    // Route::post('store/purchase/confirm/post',
    // [Store\PurchaseController::class,'confirm_post'])
    // ->name('store.purchase.confirm.post');

    // # 確認(ページの表示)
    // Route::get('store/purchase/confirm/{store_history}',
    // [Store\PurchaseController::class,'confirm'])
    // ->name('store.purchase.confirm');

    # 決済(Stripe)
    Route::post('store/purchase/stripe/checkout/',
    [Store\StripeController::class,'checkout'])
    ->name('store.purchase.stripe.checkout');

    # 完了
    Route::get('store/purchase/comp/{store_history_code}',
    [Store\PurchaseController::class,'comp'])
    ->name('store.purchase.comp');

});
