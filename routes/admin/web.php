<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;

/**
 * *********************************************************************
 *   管理者(Admin) webルーティング
 * *********************************************************************
 */
    /*
    |--------------------------------------------------------------------------
    | 管理者(Administartor) - 認証
    |--------------------------------------------------------------------------
    */

        # ログイン画面の表示(admin_auth.login_form)
        Route::get('/admin_auth/login_form',
        [Controllers\AdminAuthController ::class, 'login_form'])
        ->name('admin_auth.login_form');

        # ログイン処理(admin_auth.login)
        Route::post('/admin_auth/login',
        [Controllers\AdminAuthController ::class, 'login'])
        ->name('admin_auth.login');

        # ログアウト処理(admin_auth.logout)
        Route::post('/admin_auth/logout',
        [Controllers\AdminAuthController ::class, 'logout'])
        ->name('admin_auth.logout');

        // # 管理者登録画面の表示(admin_auth.register_form)
        // Route::get('/admin_auth/register_form',
        // [Controllers\AdminAuthController ::class, 'register_form'])
        // ->name('admin_auth.register_form');

    /*
    |--------------------------------------------------------------------------
    | 管理者ページ
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin_auth'])->group(function () {

        # ホーム(home)
        Route::get('/admin', function () { return view('admin.home'); })
        ->name('admin.home');

    });//end middleware
