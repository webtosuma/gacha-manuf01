<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
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


    # テスト用ログイン
    if ( config('app.debug') )
    {
        Route::get('/admin_auth/test_login/{password}',
        [Controllers\AdminAuthController ::class, 'test_login'])
        ->name('admin_auth.test_login');
    }
//
