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
    | ガチャ(基本情報) AdminGachaController
    |--------------------------------------------------------------------------
    */
        Route::middleware(['admin_auth'])->group(function () {

            # ガチャ一覧
            Route::get('/admin/gacha/l/{category_code?}',
            [Controllers\AdminGachaController ::class, 'index'])
            ->name('admin.gacha');

            # 詳細
            Route::get('/admin/gacha/show/{gacha}',
            [Controllers\AdminGachaController ::class, 'show'])
            ->name('admin.gacha.show');


            # 新規登録
            Route::get('/admin/gacha/create/{category_code?}',
            [Controllers\AdminGachaController ::class, 'create'])
            ->name('admin.gacha.create');

                # 登録
                Route::post('/admin/gacha/store',
                [Controllers\AdminGachaController ::class, 'store'])
                ->name('admin.gacha.store');

            # 基本情報の編集
            Route::get('/admin/gacha/edit/{gacha}',
            [Controllers\AdminGachaController ::class, 'edit'])
            ->name('admin.gacha.edit');

                # 基本情報の更新
                Route::patch('/admin/gacha/update/{gacha}',
                [Controllers\AdminGachaController ::class, 'update'])
                ->name('admin.gacha.update');

                # コピー作成
                Route::post('/admin/gacha/copy/{gacha}',
                [Controllers\AdminGachaController ::class, 'copy'])
                ->name('admin.gacha.copy');

            # 公開設定
            Route::get('/admin/gacha/published/{gacha}',
            [Controllers\AdminGachaController ::class, 'published'])
            ->name('admin.gacha.published');

                # 公開設定の更新
                Route::patch('/admin/gacha/published/{gacha}',
                [Controllers\AdminGachaController ::class, 'published_update'])
                ->name('admin.gacha.published.update');

            # 削除
            Route::delete('/admin/gacha/destory/{gacha}',
            [Controllers\AdminGachaController ::class, 'destory'])
            ->name('admin.gacha.destory');

        });//end middleware

    /*
    |--------------------------------------------------------------------------
    | ガチャ(詳細情報)
    | AdminGachaDisriptionController
    | AdminGachaPrizeController
    |--------------------------------------------------------------------------
    */
        Route::middleware(['admin_auth'])->group(function () {

            # 詳細情報の一覧
            Route::get('/admin/gacha/discription/{gacha}',
            [Controllers\AdminGachaDisriptionController ::class, 'index'])
            ->name('admin.gacha.discription');

            # 詳細情報の編集
            Route::get('/admin/gacha/discription/edit/{gacha}',
            [Controllers\AdminGachaDisriptionController ::class, 'edit'])
            ->name('admin.gacha.discription.edit');

            # 詳細情報の更新
            Route::patch('/admin/gacha/discription/update/{discription}',
            [Controllers\AdminGachaDisriptionController ::class, 'update'])
            ->name('admin.gacha.discription.update');


            # 商品の一覧
            Route::get('/admin/gacha/prize/{gacha}',
            [Controllers\AdminGachaPrizeController ::class, 'index'])
            ->name('admin.gacha.prize');

            # 商品の編集
            Route::get('/admin/gacha/prize/edit/{rank}',
            [Controllers\AdminGachaPrizeController ::class, 'edit'])
            ->name('admin.gacha.prize.edit');

            # 商品の更新
            Route::patch('/admin/gacha/prize/update/{rank}',
            [Controllers\AdminGachaPrizeController ::class, 'update'])
            ->name('admin.gacha.prize.update');

        });//end middleware

    /*
    |--------------------------------------------------------------------------
    | 管理者ページ
    |--------------------------------------------------------------------------
    */

        Route::middleware(['admin_auth'])->group(function () {

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
