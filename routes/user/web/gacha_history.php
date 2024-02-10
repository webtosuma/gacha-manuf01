<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ガチャ履歴
|  GachaHistoryController
|--------------------------------------------------------------------------
*/



Route::middleware(['auth'])->group(function () {

    # ガチャ履歴
    Route::get('gacha_history', function(){
        return view('gacha_history.index');
    })
    // [Controllers\GachaHistoryController::class, 'index'])
    ->name('gacha_history');

});
