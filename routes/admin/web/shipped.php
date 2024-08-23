<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
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

        # 発送待ち　CSVファイルのダウンロード
        Route::get('/admin/shipped/waiting/dl/csv',
        [Controllers\AdminShippedWaitingController::class, 'dl_csv'])
        ->name('admin.shipped.waiting.dl_csv');


    # 発送済み
    Route::get('/admin/shipped/send',
    [Controllers\AdminShippedSendController ::class, 'index'])
    ->name('admin.shipped.send');

        # 発送済み 詳細
        Route::get('/admin/shipped/send/{user_shipped}',
        [Controllers\AdminShippedSendController ::class, 'show'])
        ->name('admin.shipped.send.show');
});
