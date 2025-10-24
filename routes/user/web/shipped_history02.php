<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 発送申請履歴 ShippedController
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','user_rank'])->group(function () {

    # 一覧
    Route::get('shipped',
    [Controllers\ShippedController::class, 'index'])
    ->name('shipped');

    # 詳細
    Route::get('shipped/show/{user_shipped}',
    [Controllers\ShippedController::class, 'show'])
    ->name('shipped.show');


    # API 一覧
    Route::post('api/shipped',
    [Controllers\ShippedController::class,'api_index'])
    ->name('shipped.api');

});
