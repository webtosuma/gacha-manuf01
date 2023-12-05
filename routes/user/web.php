<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

// # 動画テスト
// Route::get('/test/movie', function(){

//     $user_gacha_history = \App\Models\UserGachaHistory::find(1);
//     $movies = [
//         'pc'     => 'site/movie/pc/101/01.mp4',
//         'mobile' => 'site/movie/mobile/101/01.mp4',
//     ];
//     $movie_path = [
//         'pc'     => asset( 'storage/'.$movies['pc'] ),
//         'mobile' => asset( 'storage/'.$movies['mobile'] ),
//     ];
//     return view('gacha.play', compact('user_gacha_history', 'movie_path' ));
// } );


/*
==========================================================================
 ユーザールーティング　web
==========================================================================
*/

    // Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/', function(){
        return redirect()->route('gacha_category','onepiece');
    } )->name('home');

    # 予告
    // Route::get('/', function(){
    //     return view('lp');
    // } )->name('home');

/*
|--------------------------------------------------------------------------
| 認証・登録・パスワード変更
|  UserController
|--------------------------------------------------------------------------
*/
    Auth::routes();

    # ログインが必要ですページ(require_login)　
    // ※ログイン前にログインが必要なページにアクセスした際に表示されるページ
    Route::get('/require_login', function () { return view('auth.require_login'); })
    ->name('require_login');

    # パスワード変更API ステップ01(reset_pass_step01)
    Route::post('reset_pass_step01',
    [Controllers\UserController::class, 'reset_pass_step01'])
    ->name('reset_pass_step01');

    # パスワード変更API ステップ02(reset_pass_step02)
    Route::post('reset_pass_step02',
    [Controllers\UserController::class, 'reset_pass_step02'])
    ->name('reset_pass_step02');


    # 退会処理(destroy)
    Route::delete('auth/destroy',
    [Controllers\UserController::class,'destroy'])
    ->middleware(['auth'])
    ->name('auth.destroy');

    # 退会完了ページの表示(completed_destroy)
    Route::get('auth/completed_destroy',
    function () { return view('auth.completed_destroy'); })
    ->name('auth.completed_destroy');        //

/*
|--------------------------------------------------------------------------
| ガチャ
|  GachaController
|  GachaPlayController
|--------------------------------------------------------------------------
*/
    # ガチャカのテゴリー選択
    Route::get('/g/{category_code?}',
    [App\Http\Controllers\GachaController::class, 'index'])
    ->name('gacha_category');

    # ガチャカの詳細表示
    Route::get('/g/{category_code}/{gacha}/{key}',
    [App\Http\Controllers\GachaController::class, 'show'])
    ->name('gacha');

    Route::middleware(['auth'])->group(function () {
        # ガチャカで遊ぶ
        Route::post('/g/play/{category_code}/{gacha}/{key}',
        [App\Http\Controllers\GachaPlayController::class, 'play'])
        ->name('gacha.play');

        # ガチャカの結果表示
        Route::post('/g/result/{category_code}/{user_gacha_history}',
        [App\Http\Controllers\GachaController::class, 'result'])
        ->name('gacha.result');

        # 商品のポイント交換
        Route::patch('/g/exchange_points/{category_code}/{user_gacha_history}',
        [App\Http\Controllers\GachaController::class, 'exchange_points'])
        ->name('gacha.exchange_points');
    });


/*
|--------------------------------------------------------------------------
| ポイント購入・履歴
|  PointSailController
|  PointHistoryController
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        # ポイント一覧
        Route::get('point_sail',
        [Controllers\PointSailController::class, 'index'])
        ->name('point_sail');

        # 購入手続き
        Route::get('point_sail/payment/{point_sail}',
        [Controllers\PointSailController::class, 'payment'])
        ->name('point_sail.payment');

        # 新規登録
        Route::post('point_sail/create/{point_sail}',
        [Controllers\PointSailController::class, 'create'])
        ->name('point_sail.create');

        # 購入
        Route::post('point_sail/payment/{point_sail}',
        [Controllers\PointSailController::class, 'payment_post'])
        ->name('point_sail.payment_post');

        Route::get( 'payment', [Controllers\PaymentController::class, 'index'])->name('payment');
        Route::post('payment', [Controllers\PaymentController::class, 'payment']);

        // ポイント購入完了
        Route::get('point_sail/comp/{point_history}',
        [Controllers\PointSailController::class, 'comp'])
        ->name('point_sail.comp');

        # ポイント購入履歴
        Route::get('point_history/{month?}',
        [Controllers\PointHistoryController::class, 'index'])
        ->name('point_history');

        # カード情報の削除
        Route::delete('point_sail/destroy/{point_sail}',
        [Controllers\PointSailController::class, 'destroy'])
        ->name('point_sail.destroy');

    });

/*
|--------------------------------------------------------------------------
| 取得した商品
|  UserPrizeController
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        # 商品一覧
        Route::get('user_prize',
        [Controllers\UserPrizeController::class, 'index'])
        ->name('user_prize');

        # 商品のポイント交換
        Route::patch('user_prize/exchange_points',
        [Controllers\UserPrizeController::class, 'exchange_points'])
        ->name('user_prize.exchange_points');

    });

/*
|--------------------------------------------------------------------------
| 発送申請　ShippedAppliController
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        # 発送申請入力
        Route::post('shipped/appli',
        [Controllers\ShippedAppliController::class, 'index'])
        ->name('shipped.appli');

        # 発送申請確認
        Route::post('shipped/appli/confirm',
        [Controllers\ShippedAppliController::class, 'confirm'])
        ->name('shipped.appli.confirm');

        # 発送申請完了
        Route::post('shipped/appli/comp',
        [Controllers\ShippedAppliController::class, 'comp'])
        ->name('shipped.appli.comp');

        # 発送申請完了
        Route::get('shipped/appli/comp', function(){
            return view('shipped.appli.comp');
        })->name('shipped.appli.comp.get');

    });


/*
|--------------------------------------------------------------------------
| 発送申請履歴 ShippedWaitingController ShippedSentController
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        Route::get('shipped', //発送申請履歴・発送待ちへリダイレクト
        function () { return redirect()->route('shipped.waiting'); })
        ->name('shipped');

        # 発送申請履歴・発送待ち
        Route::get('shipped/waiting',
        [Controllers\ShippedWaitingController::class, 'index'])
        ->name('shipped.waiting');

            # 発送申請履歴・発送待ち　詳細
            Route::get('shipped/waiting/{user_shipped}',
            [Controllers\ShippedWaitingController::class, 'show'])
            ->name('shipped.waiting.show');

        # 発送申請履歴・発送済み
        Route::get('shipped/send',
        [Controllers\ShippedSendController::class, 'index'])
        ->name('shipped.send');

            # 発送申請履歴・発送済み　詳細
            Route::get('shipped/send/{user_shipped}',
            [Controllers\ShippedSendController::class, 'show'])
            ->name('shipped.send.show');
        //
    });
/*
|--------------------------------------------------------------------------
| ユーザー設定
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        Route::get('settings',
        function () { return view('settings.index'); })
        ->name('settings');


        /* フォームの表示 */

            # アカウント設定(acount)
            Route::get('/settings/acount',
            function () { return  view('settings.acount.index'); })
            ->name('settings.acount');

            # クレジット情報設定(credit_card)
            Route::get('/settings/credit_card',
            [Controllers\SettingsController::class, 'credit_card'])
            ->name('settings.credit_card');

            # 商品発送先の住所設定(shipped_address)
            Route::get('/settings/shipped_address',
            function () { return  view('settings.shipped_address'); })
            ->name('settings.shipped_address');

            # メール受信設定(email_reception) 未使用
            Route::get('/settings/email_reception',
            function () { return  view('settings.email_reception'); })
            ->name('settings.email_reception');

            # 退会の手続き(withdraw)
            Route::get('/settings/withdraw',
            function () { return  view('settings.withdraw'); })
            ->name('settings.withdraw');


        /* 更新処理 */

            # アカウント情報変更(acount_update)
            Route::patch('/settings/acount/update',
            [Controllers\SettingsController::class, 'acount_update'])
            ->name('settings.acount.update');


            # クレジット情報・新規登録
            Route::post('/settings/credit_card/create',
            [Controllers\SettingsController::class, 'credit_card_create'])
            ->name('settings.credit_card.create');

            # クレジット情報・削除
            Route::delete('/settings/credit_card/destroy',
            [Controllers\SettingsController::class, 'credit_card_destroy'])
            ->name('settings.credit_card.destroy');


            # メール受信設定(email_reception_update) 未使用
            Route::patch('/settings/email_reception/update',
            [Controllers\SettingsController::class, 'email_reception_update'])
            ->name('settings.email_reception.update');

    });

/*
|--------------------------------------------------------------------------
| 求職者(Worker) - フッターメニュー
|--------------------------------------------------------------------------
*/
    # ガイド(guide)
    Route::get('guide',
    function () { return view('footer_menu.guide.index'); })
    ->name('guide');

    # 利用規約(trems)
    Route::get('/trems/{revision_date?}',
    function ($revision_date='2023-12-01')
    { return view('footer_menu.trems.index', compact('revision_date') )
    ->with('affiliate_key',session('affiliate_key') ?? '');} )
    ->name('trems');

    # プライバシーポリシー(privacy_policy)
    Route::get('/privacy_policy/{revision_date?}',
    function ($revision_date='2023-12-01') {
    return view('footer_menu.privacy_policy.index', compact('revision_date') )
    ->with('affiliate_key',session('affiliate_key') ?? '');} )
    ->name('privacy_policy');

    # 特定商取引法に基づく表記(tradelaw)
    Route::get('tradelaw',
    function () { return view('footer_menu.tradelaw.index'); })
    ->name('tradelaw');

    # お知らせ(news)
    Route::get('news',
    function () { return view('footer_menu.news.index'); })
    ->name('news');

    # お問い合わせ(contact)
    Route::get('/contact', function(){ return view('footer_menu.contact.index'); })
    ->name('contact');

    # 運営会社(operating_company)
    Route::get('/operating_company', function () {
        return redirect('https://fobees.jp/');
    })->name('operating_company');

//




