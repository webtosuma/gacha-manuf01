<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

/*
==========================================================================
 ユーザールーティング　web
==========================================================================
*/
// use App\Http\Controllers\SendMailController;
// Route::get('test', function(\Illuminate\Http\Request $request){

//     $user = \App\Models\User::withTrashed()->where('email','taku19931121@gmail.com')->first();
//     dd($user);


// } );



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


    ## (Stripe・プロジェクト内で購入処理の実行)
    // include('web/stripe_inner.php');

    ## (Stripe・React)
    // include('web/stripe_react.php');


    ##(webhook)
    include('web/stripe.php');

    # Stripe 照明URL
    Route::get('.well-known/apple-developer-merchantid-domain-association', function(){
        #jp
        // return view('point_sail.stripe.apple-developer-merchantid-domain-association.jp');
        #online
        // return view('point_sail.stripe.apple-developer-merchantid-domain-association.online');
    });

//


# 取得した商品
include('web/user_prize.php');

# 発送申請
include('web/shipped.php');

# ガチャ履歴
include('web/gacha_history.php');

# 発送申請履歴
include('web/shipped_history.php');

# ユーザー設定
include('web/settings.php');

# キャンペーン
include('web/canpaing.php');

# ユーザー設定
include('web/footer_menu.php');


