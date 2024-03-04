<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| チケット ストアー
|  TicketStoreController
|--------------------------------------------------------------------------
*/
    Route::middleware(['user_rank'])->group(function () {

        # 一覧
        Route::get('ticket_store',
        [Controllers\TicketStoreController::class, 'index'])
        ->name('ticket_store');

        # 詳細表示
        Route::get('ticket_store/s/{store}',
        [Controllers\TicketStoreController::class, 'show'])
        ->name('ticket_store.show');

    });
    Route::middleware(['auth','user_rank'])->group(function () {

        # 買い物かご

        # 購入確認

        # 購入完了
    });
