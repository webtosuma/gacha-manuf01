<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;

/**
 * *********************************************************************
 *   管理者(Admin) webルーティング
 * *********************************************************************
 */

    # ホーム(home)
    Route::get('/admin',
    [Controllers\AdminHomeController::class,'index'])
    ->middleware('admin_auth')
    ->name('admin.home');


    # 認証
    include('web/auth.php');

    # カテゴリー
    include('web/category.php');

    # 商品管理
    include('web/prize.php');

    # 販売ポイント
    include('web/point_sail.php');

    # 演出動画
    include('web/movie.php');

    # お知らせ
    include('web/infomation.php');



    # ガチャ
    include('web/gacha/index.php');//(基本情報)
    include('web/gacha/detail.php');//(詳細情報)

    # ポイント売上
    include('web/point_history.php');

    # 発送受付
    include('web/shipped.php');

    #登録ユーザー
    include('web/user/index.php');//一覧
    include('web/user/detail.php');//詳細
    include('web/user/user_prize.php');//商品履歴
    include('web/user/point_history.php');//ポイント履歴
    include('web/user/user_rank_history.php');//会員ランク履歴
    include('web/user/ticket_history.php');//チケット履歴


    # お問い合わせ一覧
    Route::get('/admin/contact', function () { return view('admin.contact.index'); })
    ->middleware('admin_auth')
    ->name('admin.contact');


    # 管理者設定
    include('web/register.php');



//
