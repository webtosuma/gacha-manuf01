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

    # 査定
    Route::post('/purchase/appraisal',
    [Controllers\PurchaseController ::class, 'appraisal'])
    ->name('purchase.appraisal');



});//end middleware
/*
|--------------------------------------------------------------------------
| 買取表 API
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 一覧情報の発行API(admin_list)
    Route::post('/purchase/api',
    [Controllers\PurchaseController::class, 'api'])
    ->name('purchase.api');


});//end middleware
