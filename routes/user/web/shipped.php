<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 発送申請　ShippedAppliController
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    # 発送申請入力
    Route::post('shipped/appli',
    [Controllers\ShippedAppliController::class, 'index'])
    ->name('shipped.appli');

    # 発送申請確認
    Route::post('shipped/appli/confirm',
    [Controllers\ShippedAppliController::class, 'confirm'])
    ->name('shipped.appli.confirm');

    # 発送申請完了
    Route::post('shipped/appli/comp',
    [Controllers\ShippedAppliController::class, 'comp'])
    ->name('shipped.appli.comp');

    # 発送申請完了//get
    Route::get('shipped/appli/comp', function(){
        return view('shipped.appli.comp');
    })->name('shipped.appli.comp.get');

});
