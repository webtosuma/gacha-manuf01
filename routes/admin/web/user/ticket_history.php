<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin ユーザー　チケット履歴 AdminUserTicketHistoryController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # チケット履歴(個人・全体)
    Route::get('/admin/user/ticket_history/{user_id}',
    [Controllers\AdminUserTicketHistoryController ::class, 'index'])
    ->name('admin.user.ticket_history');


    # すべて更新
    Route::get('/admin/user/ticket_history/all_update',
    [Controllers\AdminUserTicketHistoryController ::class, 'all_update'])
    ->name('admin.user.all_update');

});//end middleware
