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
    function ($revision_date='2024-01-15'){
         return view('footer_menu.trems.index', compact('revision_date') );
    })->name('trems');

    # プライバシーポリシー(privacy_policy)
    Route::get('/privacy_policy/{revision_date?}',
    function ($revision_date='2024-01-15') {
        return view('footer_menu.privacy_policy.index', compact('revision_date') );
    })->name('privacy_policy');

    # 特定商取引法に基づく表記(tradelaw)
    Route::get('tradelaw/{revision_date?}',
    function ($revision_date='2024-02-14') {
        return view('footer_menu.tradelaw.index', compact('revision_date'));
    })->name('tradelaw');

    # お知らせ(news)
    Route::get('infomation',
    [App\Http\Controllers\InfomationController::class,'index'])
    ->name('infomation');

        Route::get('infomation/{infomation}',
        [App\Http\Controllers\InfomationController::class,'show'])
        ->name('infomation.show');

    # お問い合わせ(contact)
    Route::get('/contact', function(){ return view('footer_menu.contact.index'); })
    ->name('contact');

    # 運営会社(operating_company)
    Route::get('/operating_company', function () {
        return redirect('https://fobees.jp/');
    })->name('operating_company');

//
