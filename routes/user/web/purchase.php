<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 買取表
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','user_rank'])->group(function () {

    # 一覧
    Route::get('/purchase',
    [Controllers\PurchaseController ::class, 'index'])
    ->name('purchase');

        # 詳細
        Route::get('/purchase/show',
        [Controllers\PurchaseController ::class, 'show'])
        ->name('purchase.show');



});//end middleware
/*
|--------------------------------------------------------------------------
| 買取表 API
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 一覧情報の発行API(admin_list)
    Route::post('/purchase/api',
    [Controllers\ApiPurchaseController::class, 'index'])
    ->name('purchase.api');


});//end middleware
