<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/*
==========================================================================
 EC　ルーティング　web
==========================================================================
*/

Route::middleware([ /* ミドルウェアー */
    'maintenance',        //メンテナンス
    'user_plize_deadline',//ユーザー商品期限切れ対応
    'user_point_deadline',//ユーザーポイント期限切れ対応
])->group(function () {



    # トップページ(ECのみのとき)
    if( config('app.layout_app')=='store' ){
        Route::get('/',
        [Store\StoreController::class, 'index'])
        ->name('home');
    }


    # 商品一覧
    include('web/index.php');

    # 買い物カート
    include('web/keep.php');

    # 購入手続き(Stripe)
    include('web/purchase.php');


    # 購入商品
    include('web/purchased.php');

    # 発送履歴
    include('web/shipped.php');

    # ポイント購入履歴
    include('web/point_history.php');

    # お知らせ(news)
    Route::get('store/infomation',
    [App\Http\Controllers\InfomationController::class,'store_index'])
    ->name('store.infomation');


});//end middleware
