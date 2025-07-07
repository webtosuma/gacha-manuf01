<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

/*
==========================================================================
 EC　ルーティング　web
==========================================================================
*/

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







