<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 発送申請履歴 ShippedWaitingController ShippedSentController
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','user_rank'])->group(function () {

    Route::get('shipped', //発送申請履歴・発送待ちへリダイレクト
    function () { return redirect()->route('shipped.waiting'); })
    ->name('shipped');

    # 発送申請履歴・発送待ち
    Route::get('shipped/waiting',
    [Controllers\ShippedWaitingController::class, 'index'])
    ->name('shipped.waiting');

        # 発送申請履歴・発送待ち　詳細
        Route::get('shipped/waiting/{user_shipped}',
        [Controllers\ShippedWaitingController::class, 'show'])
        ->name('shipped.waiting.show');

    # 発送申請履歴・発送済み
    Route::get('shipped/send',
    [Controllers\ShippedSendController::class, 'index'])
    ->name('shipped.send');

        # 発送申請履歴・発送済み　詳細
        Route::get('shipped/send/{user_shipped}',
        [Controllers\ShippedSendController::class, 'show'])
        ->name('shipped.send.show');
    //
});
