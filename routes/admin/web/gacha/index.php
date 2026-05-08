<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| ガチャ(基本情報) AdminGachaController AdminGachaCopyController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # ガチャ一覧
    Route::get('/admin/gacha/l/{category_code?}',
    [Admin\GachaController ::class, 'index'])
    ->name('admin.gacha');

    # 詳細
    Route::get('/admin/gacha/show/{gacha}',
    [Admin\GachaController ::class, 'show'])
    ->name('admin.gacha.show');


    # 新規登録
    Route::get('/admin/gacha/create/{category_code?}',
    [Admin\GachaController ::class, 'create'])
    ->name('admin.gacha.create');

        # 登録
        Route::post('/admin/gacha/store',
        [Admin\GachaController ::class, 'store'])
        ->name('admin.gacha.store');

    # 基本情報の編集
    Route::get('/admin/gacha/edit/{gacha}',
    [Admin\GachaController ::class, 'edit'])
    ->name('admin.gacha.edit');

        # 基本情報の更新
        Route::patch('/admin/gacha/update/{gacha}',
        [Admin\GachaController ::class, 'update'])
        ->name('admin.gacha.update');

    # 公開設定
    Route::get('/admin/gacha/published/{gacha}',
    [Admin\GachaController ::class, 'published'])
    ->name('admin.gacha.published');

        # 公開設定の更新
        Route::patch('/admin/gacha/published/{gacha}',
        [Admin\GachaController ::class, 'published_update'])
        ->name('admin.gacha.published.update');

    # 削除
    Route::delete('/admin/gacha/destroy/{gacha}',
    [Admin\GachaController ::class, 'destroy'])
    ->name('admin.gacha.destroy');

    # コピー作成
    Route::post('/admin/gacha/copy/{gacha}',
    [Admin\GachaCopyController ::class, 'index'])
    ->name('admin.gacha.copy');


    # 履歴
    Route::get('/admin/gacha/history/{gacha}',
    [Admin\GachaController ::class, 'history'])
    ->name('admin.gacha.history');

    # 商品履歴
    Route::get('/admin/gacha/prize_history/{gacha}',
    [Admin\GachaController ::class, 'prize_history'])
    ->name('admin.gacha.prize_history');

});//end middleware
