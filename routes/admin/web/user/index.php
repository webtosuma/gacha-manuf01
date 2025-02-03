<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 登録ユーザー一覧
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 登録ユーザー一覧
    Route::get('/admin/user/l',
    [Controllers\AdminUserController ::class, 'index'])
    ->name('admin.user');


    # CSVファイルのダウンロード
    Route::get('/admin/user/download/csv',
    [Controllers\AdminUserController ::class, 'download_csv'])
    ->name('admin.user.download_csv');

    # ポイント付与
    Route::patch('/admin/user/add_point/{user}',
    [Controllers\AdminUserController::class, 'add_point'])
    ->name('admin.user.add_point');

    # チケット付与
    Route::patch('/admin/user/add_ticket/{user}',
    [Controllers\AdminUserController::class, 'add_ticket'])
    ->name('admin.user.add_ticket');

    # 紹介キャンペーン一覧
    Route::get('/admin/user/canpaing_introductory/',
    [Controllers\AdminUserController ::class, 'canpaing_introductory'])
    ->name('admin.user.canpaing_introductory');


    # その他メニュー
    Route::get('/admin/user/other_menu/',
    [Controllers\AdminUserController ::class, 'other_menu'])
    ->name('admin.user.other_menu');

});//end middleware
