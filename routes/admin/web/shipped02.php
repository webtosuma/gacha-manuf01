<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 発送受付
| AdminShippedController
| AdminApiShippedController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 発送受付け
    Route::get('/admin/shipped',
    [Controllers\AdminShippedController::class, 'index'])
    ->name('admin.shipped');

        # 詳細
        Route::get('/admin/shipped/show/{user_shipped}',
        [Controllers\AdminShippedController::class, 'show'])
        ->name('admin.shipped.show');

        # 発送処理
        Route::patch('/admin/shipped/update',
        [Controllers\AdminShippedController ::class, 'update'])
        ->name('admin.shipped.update');

        # 追跡コードの更新
        Route::patch('/admin/shipped/update_trackingcode/{user_shipped}',
        [Controllers\AdminShippedController ::class, 'update_trackingcode'])
        ->name('admin.shipped.update_trackingcode');

        # 発送取消し
        Route::patch('/admin/shipped/cancell}',
        [Controllers\AdminShippedController ::class, 'cancell'])
        ->name('admin.shipped.cancell');

        # CSVファイルのダウンロード
        Route::get('/admin/shipped/dl_csv',
        [Controllers\AdminShippedController::class, 'dl_csv'])
        ->name('admin.shipped.dl_csv');



    # API 一覧 (AdminApiShippedController)
    Route::post('admin/api/shipped',
    [Controllers\AdminApiShippedController::class,'index'])
    ->name('admin.api.shipped');
});
