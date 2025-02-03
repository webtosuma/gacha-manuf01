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
    [Controllers\AdminUserUserPrizeController::class, 'index'])
    ->name('admin.user.user_prize');

    Route::get('/admin/user/user_prize/column/{user}',
    [Controllers\AdminUserUserPrizeController::class, 'column'])
    ->name('admin.user.user_prize.column');



    /* ユーザー商品の期限切れ */

    # (API)期限切れユーザー商品のポイント交換
    Route::post('/admin/api/user/user_prize/deadline/change_point',
    [Controllers\AdminUserUserPrizeController::class, 'api_change_point'])
    ->name('admin.api.user.user_prize.deadline.change_point');

    # ポイント交換完了
    Route::get('/admin/api/user/user_prize/deadline/comp_change_point',
    [Controllers\AdminUserUserPrizeController::class, 'comp_change_point'])
    ->name('admin.api.user.user_prize.deadline.comp_change_point');



});//end middleware
