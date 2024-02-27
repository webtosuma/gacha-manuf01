<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

/*
==========================================================================
 ユーザールーティング　web
==========================================================================
*/
Route::get('test', function(\Illuminate\Http\Request $request){

    $user = \App\Models\User::first();

    // $user_rank_history = new \App\Models\UserRankHistory([
    //     'user_id'=>$user->id,
    //     'rank_id'=>0,
    // ]);
    // $user_rank_history->save();

    # 次に昇格する会員ランク情報 next_rank
    dd($user->now_rank->next_rank);

    # 今月のランクの会員ランク情報
    dd($user->now_rank->this_rank);

    # 今月のランク
    dd($user->now_rank->toArray());


} );

Route::get('/mypage',function(){
    return view('mypage.index');
});



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

# 発送申請履歴
include('web/shipped_history.php');

# ユーザー設定
include('web/settings.php');

# キャンペーン
include('web/canpaing.php');

# ユーザー設定
include('web/footer_menu.php');


