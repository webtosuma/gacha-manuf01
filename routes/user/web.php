<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

/*
==========================================================================
 ユーザールーティング　web
==========================================================================
*/

# メンテナンス中
// Route::get('/{any?}', function()  { return view('maintenance'); })->where('any', '.*')
// ->name('maintenance');


Route::get('test', function(\Illuminate\Http\Request $request){

    $user = \App\Models\User::first();

    $user_rank_history = \App\Models\UserRankHistory::UserRanks();
    // $user_rank_history->save();
    dd($user_rank_history);
} );


Route::get('/mypage',function(){
    return view('mypage.index');
})->middleware(['auth','user_rank'])
->name('mypage');



Route::get('/',
    [App\Http\Controllers\GachaController::class, 'index']
)->middleware(['user_rank'])
->name('home');


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

# チケット購入・履歴
include('web/ticket_sail.php');

# チケット ストアー
include('web/ticket_store.php');

# 取得した商品
include('web/user_prize.php');

# 発送申請
include('web/shipped.php');

# ガチャ履歴
include('web/gacha_history.php');


# 会員ランク履歴
Route::middleware(['auth','user_rank'])->group(function () {

    Route::get('user_rank_history',
    [Controllers\UserRankHistoryController::class, 'index'])
    ->name('user_rank_history');

});

# 発送申請履歴
include('web/shipped_history.php');

# ユーザー設定
include('web/settings.php');

# キャンペーン
include('web/canpaing.php');

# ユーザー設定
include('web/footer_menu.php');


