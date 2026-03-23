<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
|--------------------------------------------------------------------------
| Manufacturer:管理者(Admin) ガチャタイトル02
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 演出動画情報の編集
    Route::get('/admin/gacha_title/movie/edit/{gacha_title}',
    [Manuf\AdminGachaTitleOtherController ::class, 'movie_edit'])
    ->name('admin.gacha_title.movie.edit');

        # 演出動画情報の更新
        Route::patch('/admin/gacha_title/movie/update/{gacha_title}',
        [Manuf\AdminGachaTitleOtherController ::class, 'movie_update'])
        ->name('admin.gacha_title.movie.update');


    # 公開設定
    Route::get('/admin/gacha_title/published/edit/{gacha_title}',
    [Manuf\AdminGachaTitleOtherController ::class, 'published_edit'])
    ->name('admin.gacha_title.published.edit');

        # 公開設定の更新
        Route::patch('/admin/gacha_title/published/{gacha_title}',
        [Manuf\AdminGachaTitleOtherController ::class, 'published_update'])
        ->name('admin.gacha_title.published.update');

    # 履歴
    Route::get('/admin/gacha_title/history/{gacha_title}',
    [Manuf\AdminGachaTitleOtherController ::class, 'history'])
    ->name('admin.gacha_title.history');

    # 商品履歴
    Route::get('/admin/gacha_title/prize_history/{gacha_title}',
    [Manuf\AdminGachaTitleOtherController ::class, 'prize_history'])
    ->name('admin.gacha_title.prize_history');


});//end middleware
