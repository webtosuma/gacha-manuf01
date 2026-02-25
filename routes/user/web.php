<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/*
==========================================================================
 ユーザールーティング　web
==========================================================================
*/
Route::get('test', function(){
    return view('test');
} );



# メンテナンス中
Route::get('maintenance',
[App\Http\Controllers\MaintenanceController::class, 'index']
)->name('maintenance');


Route::middleware([ /* ミドルウェアー */
    'maintenance',           //メンテナンス
    'user_plize_deadline',   //ユーザー商品期限切れ対応
    'user_point_deadline',   //ユーザーポイント期限切れ対応
    'user_session_validate', //1アカウント1ログイン(セッションIDチェック)
])->group(function () {


    # トップページ
    Route::get('/',
    [App\Http\Controllers\GachaController::class, 'index']
    // [App\Http\Controllers\GachaApiController::class, 'index']//非同期
    )->middleware(['user_rank'])
    ->name('home');

    # マイページ
    Route::get('/mypage',function(){
        return view('mypage.index');
    })->middleware(['auth','user_rank'])
    ->name('mypage');


    # 取得した商品
    include('web/user_prize.php');


    # WPAローディングページ
    Route::get('/pwa',function(){
        return view('pwa');
    });

    // Route::get('/ip',
    // [Controllers\Auth\RegisterController::class, 'ipTest']);


    // # 認証
    // include('web/auth.php');

    // # SNSログイン
    // include('web/auth-sns.php');

    # ガチャ
    include('web/gacha.php');

    # ガチャ商品履歴
    include('web/gacha_prize_history.php');

    # ポイント購入・履歴

        ##(Stripe・webhook)
        include('web/stripe.php');

        ##(Stripe　サブスクプラン)
        include('web/stripe_subscription.php');

        ## PayPay
        include('web/paypay.php');

        ## (fincode)
        include('web/fincode.php');

    //

    # チケット購入・履歴
    include('web/ticket_sail.php');

    # チケット ストアー
    include('web/ticket_store.php');

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
    include('web/shipped_history02.php');

    # ユーザー設定
    // include('web/settings.php');

    # キャンペーン
    include('web/canpaing.php');

    # フッターメニュー
    // include('web/footer_menu.php');

    # クーポン
    include('web/coupon.php');

    # アンケート
    include('web/survey.php');


});//end middleware
Route::middleware([ /* ミドルウェアー(メンテナンス除外) */

    'user_plize_deadline',//ユーザー商品期限切れ対応
    'user_point_deadline',//ユーザーポイント期限切れ対応

])->group(function () {

    # 認証
    include('web/auth.php');

    # SNSログイン
    include('web/auth-sns.php');

    # 二段階認証ログイン
    include('web/auth-tfa.php');

    # (Stripe・webhook)
    include('web/stripe.php');

    # (fincode)
    include('web/fincode.php');

    # ユーザー設定
    include('web/settings.php');

    # フッターメニュー
    include('web/footer_menu.php');

    # 買取表
    include('web/purchase.php');

});//end middleware
