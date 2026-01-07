<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| フッターメニュー
|--------------------------------------------------------------------------
*/
    # ガイド(guide)
    Route::get('guide',
    function () { return view('footer_menu.guide.index'); })
    ->name('guide');

    # 利用規約(trems)
    Route::get('/trems/{revision_date?}',
    function ($revision_date='2024-02-16'){
         return view('footer_menu.trems.index', compact('revision_date') );
    })->name('trems');

    # プライバシーポリシー(privacy_policy)
    Route::get('/privacy_policy/{revision_date?}',
    function ($revision_date='2024-03-01') {
        return view('footer_menu.privacy_policy.index', compact('revision_date') );
    })->name('privacy_policy');

    # 特定商取引法に基づく表記(tradelaw)
    Route::get('tradelaw/{revision_date?}',
    function ($revision_date='2024-02-14') {
        return view('footer_menu.tradelaw.index', compact('revision_date'));
    })->name('tradelaw');


    # 会員ランクとは(about_user_rank)
    Route::get('/about_user_rank',
    function () {
        return view('footer_menu.about_user_rank.index');
    })->name('about_user_rank');

    /* 規約類のDB利用 */
    if ( env('APP_DEBUG') === false )
    {
        # ガイド(guide)
        Route::get('guide',
        [App\Http\Controllers\FooterMenuController::class,'guide'])
        ->name('guide');

        # 利用規約(trems)
        Route::get('/trems/{revision_date?}',
        [App\Http\Controllers\FooterMenuController::class,'trems'])
        ->name('trems');

        # プライバシーポリシー(privacy_policy)
        Route::get('/privacy_policy/{revision_date?}',
        [App\Http\Controllers\FooterMenuController::class,'privacy_policy'])
        ->name('privacy_policy');

        # 特定商取引法に基づく表記(tradelaw)
        Route::get('tradelaw/{revision_date?}',
        [App\Http\Controllers\FooterMenuController::class,'tradelaw'])
        ->name('tradelaw');

        # 会員ランクとは(about_user_rank)
        Route::get('/about_user_rank',
        [App\Http\Controllers\FooterMenuController::class,'about_user_rank'])
        ->name('about_user_rank');
    }


 
    # お知らせ(news)
    Route::get('infomation',
    [App\Http\Controllers\InfomationController::class,'index'])
    ->name('infomation');

        // 詳細
        Route::get('infomation/{infomation}',
        [App\Http\Controllers\InfomationController::class,'show'])
        ->name('infomation.show');

        //API・一覧
        Route::post('infomation/api/list',
        [App\Http\Controllers\InfomationController::class,'api_list'])
        ->name('infomation.api.list');


    # お問い合わせ(contact)
    Route::get('/contact',
    [App\Http\Controllers\ContactController::class,'index'])
    ->name('contact');

    # タイムライン(timeline)
    Route::get('/timeline', function () {
        return view('footer_menu.timeline.index');
    })->name('timeline');

    # メールが届かないとき(not_receiving_email)
    Route::get('/not_receiving_email/{revision_date?}',
    function () {
        return view('footer_menu.not_receiving_email.index');
    })->name('not_receiving_email');

    # PWAとは(about_pwa)
    Route::get('/about_pwa/{revision_date?}',
    function () {
        return view('footer_menu.about_pwa.index');
    })->name('about_pwa');

    # 運営会社(operating_company)
    Route::get('/operating_company', function () {
        // return redirect('https://fobees.jp/');
        return redirect( config('app.company_url') );
    })->name('operating_company');

//
