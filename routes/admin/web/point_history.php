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

    Route::get('/admin/point_history/{month_text?}',
    [Controllers\AdminPointHistoryController ::class, 'index'])
    ->where('month_text', '[0-9]{4}-[0-9]{2}-01')
    ->name('admin.point_history');

    Route::get('/admin/point_history/l/datetime',
    [Controllers\AdminPointHistoryController ::class, 'datetime'])
    ->name('admin.point_history.datetime');

});

