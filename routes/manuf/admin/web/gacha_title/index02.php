<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
|--------------------------------------------------------------------------
| Manufacturer:管理者(Admin) ガチャタイトル02
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 公開設定
    Route::get('/admin/gacha_title/published/{gacha_title}',
    [Manuf\AdminGachaTitleController ::class, 'published'])
    ->name('admin.gacha_title.published');

        # 公開設定の更新
        Route::patch('/admin/gacha_title/published/{gacha_title}',
        [Manuf\AdminGachaTitleController ::class, 'published_update'])
        ->name('admin.gacha_title.published.update');

    # 演出動画情報の編集
    Route::get('/admin/gacha_title/movie/edit/{gacha}',
    [Manuf\AdminGachaTitleController ::class, 'edit'])
    ->name('admin.gacha_title.movie.edit');

        # 演出動画情報の更新
        Route::patch('/admin/gacha_title/movie/update/{gacha}',
        [Manuf\AdminGachaTitleController ::class, 'update'])
        ->name('admin.gacha_title.movie.update');


    # 商品の編集
    Route::get('/admin/gacha_title/prize/edit/{gacha}',
    [Manuf\AdminGachaTitlePrizeController ::class, 'edit'])
    ->name('admin.gacha_title.prize.edit');

        # 商品の更新
        Route::patch('/admin/gacha_title/prize/update/{gacha}',
        [Manuf\AdminGachaTitlePrizeController ::class, 'update'])
        ->name('admin.gacha_title.prize.update');


    # 履歴
    Route::get('/admin/gacha_title/history/{gacha}',
    [Manuf\AdminGachaTitleController ::class, 'history'])
    ->name('admin.gacha_title.history');

    # 商品履歴
    Route::get('/admin/gacha_title/prize_history/{gacha}',
    [Manuf\AdminGachaTitleController ::class, 'prize_history'])
    ->name('admin.gacha_title.prize_history');


});//end middleware
