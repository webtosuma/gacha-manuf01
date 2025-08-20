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

    # チケット交換商品
    include('web/ticket_store.php');

    # お知らせ
    include('web/infomation.php');

    # サイト背景の管理
    include('web/back_ground.php');

    # メンテナンス表示
    include('web/maintenance.php');

    # サブスク管理
    include('web/subscription.php');

    # クーポン
    include('web/coupon.php');

    # 操作履歴
    include('web/log.php');



    # ポイント売上
    include('web/point_history.php');
    include('web/point_sales_report.php');//改正版

    # ガチャ
    include('web/gacha/index.php');//(基本情報)
    include('web/gacha/detail.php');//(詳細情報)
    include('web/gacha/play.php');//(play)

    # スポンサー・スポンサー広告
    include('web/sponsor.php');
    include('web/sponsor_ad.php');

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
    include('web/contact.php');


    # 管理者設定
    include('web/register.php');



//
