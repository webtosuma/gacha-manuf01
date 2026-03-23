<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
|--------------------------------------------------------------------------
| Manufacturer:管理者(Admin) ガチャタイトル タイトル商品
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/gacha_title/{gacha_title}/title_prize',
    [Manuf\AdminGachaTitlePrizeController ::class, 'index'])
    ->name('admin.gacha_title.title_prize');


    // # 詳細
    // Route::get('/admin/gacha_title/{gacha_title}/title_prize/show/{title_prize}',
    // [Manuf\AdminGachaTitlePrizeController ::class, 'show'])
    // ->name('admin.gacha_title.title_prize.show');


    # 新規登録
    Route::get('/admin/gacha_title/{gacha_title}/title_prize/create',
    [Manuf\AdminGachaTitlePrizeController ::class, 'create'])
    ->name('admin.gacha_title.title_prize.create');

        # 登録
        Route::post('/admin/gacha_title/{gacha_title}/title_prize/store',
        [Manuf\AdminGachaTitlePrizeController ::class, 'store'])
        ->name('admin.gacha_title.title_prize.store');

    # 基本情報の編集
    Route::get('/admin/gacha_title/{gacha_title}/title_prize/edit/{title_prize}',
    [Manuf\AdminGachaTitlePrizeController ::class, 'edit'])
    ->name('admin.gacha_title.title_prize.edit');

        # 基本情報の更新
        Route::patch('/admin/gacha_title/{gacha_title}/title_prize/update/{title_prize}',
        [Manuf\AdminGachaTitlePrizeController ::class, 'update'])
        ->name('admin.gacha_title.title_prize.update');

    # 削除
    Route::delete('/admin/gacha_title/{gacha_title}/title_prize/destroy/{title_prize}',
    [Manuf\AdminGachaTitlePrizeController ::class, 'destroy'])
    ->name('admin.gacha_title.title_prize.destroy');

    # コピー
    Route::post('/admin/gacha_title/{gacha_title}/title_prize/copy/{title_prize}',
    [Manuf\AdminGachaTitlePrizeController ::class, 'copy'])
    ->name('admin.gacha_title.title_prize.copy');

});//end middleware
