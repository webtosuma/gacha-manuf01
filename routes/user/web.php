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
| ガチャ
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

    # ガチャカで遊ぶ
    Route::post('/g/play/{category_code}/{gacha}/{key}',
    [App\Http\Controllers\GachaPlayController::class, 'play'])
    ->name('gacha.play');

    # ガチャカの結果表示
    Route::post('/g/result/{category_code}/{user_gacha_history}',
    [App\Http\Controllers\GachaController::class, 'result'])
    ->name('gacha.result');

/*
|--------------------------------------------------------------------------
| 認証・登録・パスワード変更
|--------------------------------------------------------------------------
*/
    Auth::routes();

    # ログインが必要ですページ(require_login)　※ログイン前にログインが必要なページにアクセスした際に表示されるページ
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

    //

});



/*
|--------------------------------------------------------------------------
| 求職者(Worker) - フッターメニュー
|--------------------------------------------------------------------------
*/
    # 利用規約(trems)
    Route::get('/trems/{revision_date?}',
    function ($revision_date='2023-04-01')
    { return view('trems.index', compact('revision_date') )
    ->with('affiliate_key',session('affiliate_key') ?? '');} )
    ->name('trems');

    # プライバシーポリシー(privacy_policy)
    Route::get('/privacy_policy/{revision_date?}',
    function ($revision_date='2023-09-12') {
    return view('privacy_policy.index', compact('revision_date') )
    ->with('affiliate_key',session('affiliate_key') ?? '');} )
    ->name('privacy_policy');


    # お問い合わせ(contact)
    Route::get('/contact', function(){ return view('contact.index'); })
    ->name('contact');

        # お問い合わせコンポーネント用データAPI(component_data_api)
        Route::post('/contact/component_data_api',
        [Controllers\ContactController::class, 'component_data_api'])
        ->name('contact.component_data_api');

        # お問い合わせ[完了]API(completion_api)
        Route::post('contact/completion_api',
        [Controllers\ContactController::class, 'completion_api'])
        ->name('contact.completion_api');


    // # よくある質問(faq)
    // Route::get('/faq', function () { return view('worker.faq.list')
    // ->with('affiliate_key',session('affiliate_key') ?? '');} )
    // ->name('faq');


    # 運営会社(operating_company)
    Route::get('/operating_company', function () {
        return redirect('https://tosuma.ltd/');
    })->name('operating_company');

//




