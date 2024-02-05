<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
/*
==========================================================================
 ユーザールーティング　web
==========================================================================
*/


Route::get('/',
    [App\Http\Controllers\GachaController::class, 'index']
)->name('home');


# WPAローディングページ
Route::get('/pwa',function(){
    return view('pwa');
});

Route::get('/ip',
[Controllers\Auth\RegisterController::class, 'ipTest']);


# 認証
include('web/auth.php');

# ガチャ
include('web/gacha.php');


# ポイント購入・履歴

    ##(webhook)
    // include('web/stripe.php');

    ## (Stripe・プロジェクト内で購入処理の実行)
    include('web/stripe_inner.php');

//


# 取得した商品
include('web/user_prize.php');

# 発送申請
include('web/shipped.php');

# 発送申請履歴
include('web/shipped_history.php');

# ユーザー設定
include('web/settings.php');

# キャンペーン
include('web/canpaing.php');

# ユーザー設定
include('web/footer_menu.php');


