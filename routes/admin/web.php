<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;

/**
 * *********************************************************************
 *   管理者(Admin) webルーティング
 * *********************************************************************
 */
        # ホーム(home)
        Route::get('/admin', function () { return view('admin.home'); })
        ->middleware('admin_auth')
        ->name('admin.home');

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
    | 景品管理
    |--------------------------------------------------------------------------
    */
        Route::middleware(['admin_auth'])->group(function () {

            # 一覧
            Route::get('/admin/prize', function () { return view('admin.prize.index'); })
            ->name('admin.prize');

            # 新規作成
            Route::get('/admin/prize/create',
            [Controllers\AdminPrizeController ::class, 'create'])
            ->name('admin.prize.create');

            # 登録
            Route::post('/admin/prize/store',
            [Controllers\AdminPrizeController ::class, 'store'])
            ->name('admin.prize.store');

            # 編集
            Route::get('/admin/prize/edit/{prize?}',//?:componentで利用
            [Controllers\AdminPrizeController ::class, 'edit'])
            ->name('admin.prize.edit');

            # 更新
            Route::patch('/admin/prize/update/{prize}',
            [Controllers\AdminPrizeController ::class, 'update'])
            ->name('admin.prize.update');


        });//end middleware

    /*
    |--------------------------------------------------------------------------
    | 管理者ページ
    |--------------------------------------------------------------------------
    */

        Route::middleware(['admin_auth'])->group(function () {






            # ガチャ管理
            Route::get('/admin/gacha', function () { return view('admin.gacha.index'); })
            ->name('admin.gacha');

                # 新規登録
                Route::get('/admin/gacha/create', function () { return view('admin.gacha.create'); })
                ->name('admin.gacha.create');


            # ポイント管理
            Route::get('/admin/point', function () { return view('admin.point.index'); })
            ->name('admin.point');

            # 発送受付け
            Route::get('/admin/shipped', function () { return view('admin.shipped.index'); })
            ->name('admin.shipped');

            # 登録ユーザー一覧
            Route::get('/admin/user', function () { return view('admin.user.index'); })
            ->name('admin.user');

        });//end middleware
