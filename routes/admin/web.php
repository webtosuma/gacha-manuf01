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


    /*
    |--------------------------------------------------------------------------
    | カテゴリー GachaCategory
    |--------------------------------------------------------------------------
    */
        Route::middleware(['admin_auth'])->group(function () {

            # 一覧
            Route::get('/admin/category',
            [Controllers\AdminGachaCategoryController ::class, 'index'])
            ->name('admin.category');

            # 表示
            Route::get('/admin/category/show/{category}',
            [Controllers\AdminGachaCategoryController::class,'show'])
            ->name('admin.category.show');

            # 新規登録
            Route::get('/admin/category/create',
            [Controllers\AdminGachaCategoryController::class, 'create'])
            ->name('admin.category.create');

                # 登録
                Route::post('/admin/category/store',
                [Controllers\AdminGachaCategoryController::class, 'store'])
                ->name('admin.category.store');

            # 基本情報の編集
            Route::get('/admin/category/edit/{category}',
            [Controllers\AdminGachaCategoryController::class, 'edit'])
            ->name('admin.category.edit');

                # 基本情報の更新
                Route::patch('/admin/category/update/{category}',
                [Controllers\AdminGachaCategoryController::class, 'update'])
                ->name('admin.category.update');

            # 削除
            Route::delete('/admin/category/destroy/{category}',
            [Controllers\AdminGachaCategoryController::class, 'destroy'])
            ->name('admin.category.destroy');


        });//end middleware

    /*
    |--------------------------------------------------------------------------
    | 商品管理
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
            Route::delete('/admin/gacha/destroy/{gacha}',
            [Controllers\AdminGachaController ::class, 'destroy'])
            ->name('admin.gacha.destroy');

        });//end middleware

    /*
    |--------------------------------------------------------------------------
    | ガチャ(詳細情報)
    | AdminGachaDisriptionController
    | AdminGachaPrizeController
    |--------------------------------------------------------------------------
    */
        Route::middleware(['admin_auth'])->group(function () {

            # ガチャの詳細情報
            Route::get('/admin/gacha/prize/{gacha}',function(\App\Models\Gacha $gacha) {
                return view('admin.gacha.prize.show', compact('gacha'));
            })->name('admin.gacha.prize.show');

            # 商品の編集
            Route::get('/admin/gacha/prize/edit/{gacha}',
            [Controllers\AdminGachaPrizeController ::class, 'edit'])
            ->name('admin.gacha.prize.edit');

                # 商品の更新
                Route::patch('/admin/gacha/prize/update/{gacha}',
                [Controllers\AdminGachaPrizeController ::class, 'update'])
                ->name('admin.gacha.prize.update');


            # 演出動画情報の編集
            Route::get('/admin/gacha/movie/edit/{gacha}',
            [Controllers\AdminGachaMovieController ::class, 'edit'])
            ->name('admin.gacha.movie.edit');

                # 演出動画情報の更新
                Route::patch('/admin/gacha/movie/update/{gacha}',
                [Controllers\AdminGachaMovieController ::class, 'update'])
                ->name('admin.gacha.movie.update');

            # 詳細情報の編集
            Route::get('/admin/gacha/discription/edit/{gacha}',
            [Controllers\AdminGachaDisriptionController ::class, 'edit'])
            ->name('admin.gacha.discription.edit');

                # 詳細情報の更新
                Route::patch('/admin/gacha/discription/update/{gacha}',
                [Controllers\AdminGachaDisriptionController ::class, 'update'])
                ->name('admin.gacha.discription.update');



        });//end middleware


    /*
    |--------------------------------------------------------------------------
    | ポイント売上
    | AdminPointHistoryController
    |--------------------------------------------------------------------------
    */
        Route::middleware(['admin_auth'])->group(function () {

            Route::get('/admin/point_history/{month_text?}',
            [Controllers\AdminPointHistoryController ::class, 'index'])
            ->where('month_text', '[0-9]{4}-[0-9]{2}-01')
            ->name('admin.point_history');

        });

    /*
    |--------------------------------------------------------------------------
    | 発送受付
    | AdminShippedWaitingController
    | AdminShippedSendController
    |--------------------------------------------------------------------------
    */
        Route::middleware(['admin_auth'])->group(function () {

            # 発送受付け
            Route::get('/admin/shipped',
            function(){ return redirect()->route('admin.shipped.waiting'); } )
            ->name('admin.shipped');

            # 発送待ち
            Route::get('/admin/shipped/waiting',
            [Controllers\AdminShippedWaitingController ::class, 'index'])
            ->name('admin.shipped.waiting');

                # 発送待ち 詳細
                Route::get('/admin/shipped/waiting/{user_shipped}',
                [Controllers\AdminShippedWaitingController ::class, 'show'])
                ->name('admin.shipped.waiting.show');

                # 発送待ち 発送処理
                Route::patch('/admin/shipped/waiting/{user_shipped}',
                [Controllers\AdminShippedWaitingController ::class, 'update'])
                ->name('admin.shipped.waiting.update');

            # 発送済み
            Route::get('/admin/shipped/send',
            [Controllers\AdminShippedSendController ::class, 'index'])
            ->name('admin.shipped.send');

                # 発送済み 詳細
                Route::get('/admin/shipped/send/{user_shipped}',
                [Controllers\AdminShippedSendController ::class, 'show'])
                ->name('admin.shipped.send.show');
        });

    /*
    |--------------------------------------------------------------------------
    | 登録ユーザー一覧
    |--------------------------------------------------------------------------
    */

        Route::middleware(['admin_auth'])->group(function () {

            # 登録ユーザー一覧
            Route::get('/admin/user',
            [Controllers\AdminUserController ::class, 'index'])
            ->name('admin.user');


            # ポイント付与
            Route::patch('/admin/user/add_point/{user}',
            [Controllers\AdminUserController ::class, 'add_point'])
            ->name('admin.user.add_point');

        });//end middleware
    /*
    |--------------------------------------------------------------------------
    | お問い合わせ
    |--------------------------------------------------------------------------
    */

        Route::middleware(['admin_auth'])->group(function () {

            # お問い合わせ一覧
            Route::get('/admin/contact', function () { return view('admin.contact.index'); })
            ->name('admin.contact');

        });//end middleware


    /*
    |--------------------------------------------------------------------------
    | お知らせ
    |--------------------------------------------------------------------------
    */
        Route::middleware(['admin_auth'])->group(function () {

            # 一覧
            Route::get('/admin/infomation',
            [Controllers\AdminInfomationController::class,'index'])
            ->name('admin.infomation');

            # 表示
            Route::get('/admin/infomation/show/{infomation}',
            [Controllers\AdminInfomationController::class,'show'])
            ->name('admin.infomation.show');

            # 新規登録
            Route::get('/admin/infomation/create',
            [Controllers\AdminInfomationController::class, 'create'])
            ->name('admin.infomation.create');

                # 登録
                Route::post('/admin/infomation/store',
                [Controllers\AdminInfomationController ::class, 'store'])
                ->name('admin.infomation.store');

            # 基本情報の編集
            Route::get('/admin/infomation/edit/{infomation}',
            [Controllers\AdminInfomationController ::class, 'edit'])
            ->name('admin.infomation.edit');

                # 基本情報の更新
                Route::patch('/admin/infomation/update/{infomation}',
                [Controllers\AdminInfomationController ::class, 'update'])
                ->name('admin.infomation.update');

            # 削除
            Route::delete('/admin/infomation/destroy/{infomation}',
            [Controllers\AdminInfomationController ::class, 'destroy'])
            ->name('admin.infomation.destroy');

        });//end middleware


    /*
    |--------------------------------------------------------------------------
    | 管理者設定
    |--------------------------------------------------------------------------
    */

        Route::middleware(['admin_auth'])->group(function () {

            # 管理者一覧の表示(register)
            Route::get('admin/register',
            [App\Http\Controllers\AdminController::class,'index'])
            ->name('admin.register');

            # 管理者登録画面の表示(create)
            Route::get('admin/register/create',
            function(){ return view('admin.register.create'); })
            ->name('admin.register.create');

            # 管理者登録処理(register_post)
            Route::post('admin/register/store',
            [App\Http\Controllers\AdminController::class,'store'])
            ->name('admin.register.store');

            # 管理者情報編集ページの表示(register_edit)
            Route::get('admin/register/edit/{admin}',
            [App\Http\Controllers\AdminController::class,'edit'])
            ->name('admin.register.edit');

            # 管理者情報の更新(register_update)
            Route::patch('admin/register/update/{admin}',
            [App\Http\Controllers\AdminController::class,'update'])
            ->name('admin.register.update');

            # 管理者情報の削除(register_destroy)
            Route::delete('admin/register/destroy/{admin}',
            [App\Http\Controllers\AdminController::class,'destroy'])
            ->name('admin.register.destroy');

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

        });//end middleware
