<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| チケット販売
|  TicketSailController
|--------------------------------------------------------------------------
*/

    # 一覧
    Route::get('ticket_sail',
    [Controllers\TicketSailController::class, 'index'])
    ->name('ticket_sail');


Route::middleware(['auth'])->group(function () {



    # 履歴
    Route::get('ticket_history',
    [Controllers\TicketHistoryController::class, 'index'])
    ->name('ticket_history');

});

