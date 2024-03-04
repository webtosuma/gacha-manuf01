<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\UserGachaHistory;
/*
|--------------------------------------------------------------------------
| ガチャ履歴
|  GachaHistoryController
|--------------------------------------------------------------------------
*/


Route::middleware(['auth','user_rank'])->group(function () {

    # ガチャ履歴
    Route::get('gacha_history', function(){

        $gacha_histories = UserGachaHistory::where('user_id',Auth::user()->id)
        ->orderByDesc('created_at')
        ->paginate(20);


        return view('gacha_history.index',compact('gacha_histories'));
    })
    // [Controllers\GachaHistoryController::class, 'index'])
    ->name('gacha_history');

});
