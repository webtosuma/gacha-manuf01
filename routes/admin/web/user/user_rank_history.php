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

    # 会員ランクの更新(個人)
    Route::post('/admin/user/user_rank_history/update/{user}',
    [Controllers\AdminUserUserRankHistoryController ::class, 'update'])
    ->name('admin.user.user_rank_history.update');

    # 会員ランクの更新(全員)
    Route::post('/admin/user/user_rank_history/all_update/',
    [Controllers\AdminUserUserRankHistoryController ::class, 'all_update'])
    ->name('admin.user.user_rank_history.all_update');

});//end middleware
