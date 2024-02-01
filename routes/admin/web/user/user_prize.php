<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ユーザー商品履歴(個人・全体)　AdminUserUserPrizeController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # ユーザー商品履歴(個人・全体)
    Route::get('/admin/user/user_prize/{user_id}',
    [Controllers\AdminUserUserPrizeController::class, 'index'])//AdminUserUserPrizeController
    ->name('admin.user.user_prize');

    Route::get('/admin/user/user_prize/column/{user}',
    [Controllers\AdminUserUserPrizeController::class, 'column'])//AdminUserUserPrizeController
    ->name('admin.user.user_prize.column');


});//end middleware
