<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin ユーザー　会員ランク履歴 AdminUserUserRankHistoryController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 会員ランク履歴(個人)
    Route::get('/admin/user/user_rank_history/{user}',
    [Controllers\AdminUserUserRankHistoryController ::class, 'index'])
    ->name('admin.user.user_rank_history');


});//end middleware
