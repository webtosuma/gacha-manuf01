<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 商品管理
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/prize/list/{category_id?}',
    [Controllers\AdminPrizeController ::class, 'index'])
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


    # CSVファイルのダウンロード
    Route::post('/admin/prize/download/csv',
    [Controllers\AdminPrizeController ::class, 'download_csv'])
    ->name('admin.prize.download_csv');


    # CSVインポート
    Route::get('/admin/prize/import/csv',
    [Controllers\AdminPrizeController ::class, 'import_csv'])
    ->name('admin.prize.import_csv');

        # CSVインポート処理
        Route::post('/admin/prize/import/csv/post',
        [Controllers\AdminPrizeController ::class, 'import_csv_post'])
        ->name('admin.prize.import_csv_post');

        # インポート用CSVファイルダウンロード
        Route::get('/admin/prize/import/csv/download',
        [Controllers\AdminPrizeController ::class, 'import_csv_download'])
        ->name('admin.prize.import_csv_download');

});//end middleware
