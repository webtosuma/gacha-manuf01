<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;



Route::middleware(['admin_auth'])->group(function () {

    # ガチャカテゴリー情報の取得
    Route::post('admin/api/gacha/category',
    [Controllers\AdminApiGatyaController::class, 'category'])
    ->name('admin.api.gacha.category');

    # 商品一覧情報の取得
    Route::post('admin/api/prize',
    [Controllers\AdminApiPrizeController::class, 'index'])
    ->name('admin.api.prize');

    # 商品情報の削除
    Route::delete('admin/api/prize/{prize?}',
    [Controllers\AdminApiPrizeController::class, 'destroy'])
    ->name('admin.api.prize.destroy');


    # 商品ランク情報の取得



});
