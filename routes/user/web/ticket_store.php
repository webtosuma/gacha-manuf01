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
        Route::get('ticket_store/show/{store}',
        [Controllers\TicketStoreController::class, 'show'])
        ->name('ticket_store.show');

    });
    Route::middleware(['auth','user_rank'])->group(function () {

        # チケット交換処理
        Route::post('ticket_store/post/{store}',
        [Controllers\TicketStoreController::class, 'post'])
        ->name('ticket_store.post');

        # 交換完了完了
        Route::get('ticket_store/comp/{ticket_history}/{key}',
        [Controllers\TicketStoreController::class, 'comp'])
        ->name('ticket_store.comp');

    });
