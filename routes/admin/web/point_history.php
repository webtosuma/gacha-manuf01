<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ポイント売上
| AdminPointHistoryController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 月別レポート
    Route::get('/admin/point_history/{month_text?}',
    [Controllers\AdminPointHistoryController ::class, 'index'])
    ->where('month_text', '[0-9]{4}-[0-9]{2}-01')
    ->name('admin.point_history');

    Route::get('/admin/point_history/l/datetime',
    [Controllers\AdminPointHistoryController ::class, 'datetime'])
    ->name('admin.point_history.datetime');

    # 日別レポート
    Route::get('/admin/point_history/l/daily/{date_text}',
    [Controllers\AdminPointHistoryController ::class, 'daily'])
    ->name('admin.point_history.daily');

});

