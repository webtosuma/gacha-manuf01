<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
==========================================================================
 ユーザールーティング　web
==========================================================================
*/

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function(){
    return redirect()->route('gacha_category','onepiece');
} )->name('home');

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
    Route::post('user/reset_pass_step02',
    [Controllers\UserController::class, 'reset_pass_step02'])
    ->name('reset_pass_step02');


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

        # ポイント購入　
        Route::get('point_sail',
        [Controllers\PointSailController::class, 'index'])
        ->name('point_sail');

        Route::get('point_sail/payment/{point_sail}',
        [Controllers\PointSailController::class, 'payment'])
        ->name('point_sail.payment');

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
| 発送申請履歴 ShippedController　ShippedAppliController
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        Route::get('shipped', //発送申請履歴・発送中へリダイレクト
        function () { return redirect()->route('shipped.current'); })
        ->name('shipped');

        # 発送申請履歴・発送中
        Route::get('shipped/current',
        [Controllers\ShippedController::class, 'current'])
        ->name('shipped.current');

            # 発送申請履歴・発送中　詳細
            Route::get('shipped/current/{user_shipped}',
            [Controllers\ShippedController::class, 'current_show'])
            ->name('shipped.current.show');

        # 発送申請履歴・完了済　
        Route::get('shipped/comp',
        [Controllers\ShippedController::class, 'comp'])
        ->name('shipped.comp');

            # 発送申請履歴・完了済　詳細
            Route::get('shipped/comp/{user_shipped}',
            [Controllers\ShippedController::class, 'comp_show'])
            ->name('shipped.current.show');
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

        # お問い合わせコンポーネント用データAPI(component_data_api)
        Route::post('/contact/component_data_api',
        [Controllers\ContactController::class, 'component_data_api'])
        ->name('contact.component_data_api');

        # お問い合わせ[完了]API(completion_api)
        Route::post('contact/completion_api',
        [Controllers\ContactController::class, 'completion_api'])
        ->name('contact.completion_api');




    # 運営会社(operating_company)
    Route::get('/operating_company', function () {
        return redirect('https://fobees.jp/');
    })->name('operating_company');

//




