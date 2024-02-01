<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| 登録ユーザー詳細　AdminUserShowControlle
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 詳細
    Route::get('/admin/user/s/{user}',
    [Controllers\AdminUserShowControlle::class, 'index'])
    ->name('admin.user.show');


    # 退会処理
    Route::delete('/admin/user/destroy/{user}',
    [Controllers\AdminUserShowControlle::class, 'destroy'])
    ->name('admin.user.destroy');



});//end middleware
