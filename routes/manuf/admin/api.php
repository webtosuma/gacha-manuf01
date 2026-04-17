<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
use App\Http\Controllers;

/**
 * *********************************************************************
 *   Manufacturer:管理者(Admin) APIルーティング
 * *********************************************************************
 */
Route::middleware(['admin_auth'])->group(function () {

    # タイトル 一覧(未公開を含む)
    // Route::post('admin/api/gacha_title',
    // [Manuf\AdminApiGachaTitleController::class, 'index'])
    // ->name('admin.api.gacha_title.');


    # タイトル商品のprize 一覧(筐体口数登録用)
    Route::post('admin/api/gacha_title/{gacha_title}/title_prize/prize',
    [Manuf\AdminApiGachaTitleController::class, 'title_prize_prize'])
    ->name('admin.api.gacha_title.title_prize.prize');


    # タイトル商品 一覧(未公開を含む)
    // Route::post('admin/api/gacha_title/{gacha_title}/title_prize',
    // [Manuf\AdminApiGachaTitleController::class, 'title_prize'])
    // ->name('admin.api.gacha_title.title_prize');


    # 筐体 一覧(未公開を含む)
    // Route::post('admin/api/gacha_title/{gacha_title}/machine',
    // [Manuf\AdminApiGachaTitleController::class, 'machine'])
    // ->name('admin.api.gacha_title.machine');


});//end middleware
