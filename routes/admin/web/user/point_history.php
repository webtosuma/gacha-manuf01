<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin ユーザー　ポイント履歴(個人・全体)　AdminUserPointHistoryController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # ポイント履歴(個人・全体)
    Route::get('/admin/user/point_history/{user_id}',
    [Controllers\AdminUserPointHistoryController ::class, 'index'])
    ->name('admin.user.point_history');

    # 削除確認
    Route::post('/admin/user/point_history/destroy_confirm/{user_id}',
    [Controllers\AdminUserPointHistoryController ::class, 'destroy_confirm'])
    ->name('admin.user.point_history.destroy_confirm');

    # 削除確認
    Route::delete('/admin/user/point_history/destroy/{user_id}',
    [Controllers\AdminUserPointHistoryController ::class, 'destroy'])
    ->name('admin.user.point_history.destroy');



});//end middleware
