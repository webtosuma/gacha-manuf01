<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| チケット ストアー
|  TicketStoreController
|--------------------------------------------------------------------------
*/

    # 一覧
    Route::get('ticket_store',
    [Controllers\TicketStoreController::class, 'index'])
    ->name('ticket_store');


Route::middleware(['auth'])->group(function () {


});
