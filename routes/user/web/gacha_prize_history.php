<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ガチャ商品履歴
|--------------------------------------------------------------------------
*/

    Route::middleware(['user_rank'])->group(function () {

        # ガチャの商品履歴
        Route::get('/g/prize_history/{category_code}/{gacha}/{key}',
        [Controllers\GachaPrizeHistoryController::class, 'index'])
        ->name('gacha.prize_history');

        # API ガチャの商品履歴
        Route::post('/gacha/api/prize_history/{gacha}',
        [Controllers\GachaPrizeHistoryController::class, 'api'])
        ->name('gacha.api.prize_history');


    });
