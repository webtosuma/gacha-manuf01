<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| チケット販売
|  TicketSailController
|--------------------------------------------------------------------------
*/
    Route::middleware(['user_rank'])->group(function () {

        # 一覧
        Route::get('ticket_sail',
        [Controllers\TicketSailController::class, 'index'])
        ->name('ticket_sail');

    });
    Route::middleware(['auth','user_rank'])->group(function () {

        # 購入確認

        # 購入完了


        # 履歴
        Route::get('ticket_history',
        [Controllers\TicketHistoryController::class, 'index'])
        ->name('ticket_history');

    });

