<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;



Route::middleware(['admin_auth'])->group(function () {

    # ガチャカテゴリー情報の取得
    Route::post('admin/api/gacha/category',
    [Controllers\AdminApiGatyaController::class, 'category'])
    ->name('admin.api.gacha.category');

    # ガチャ一覧の取得
    Route::post('admin/api/gacha',
    [Controllers\AdminApiGatyaController::class, 'list'])
    ->name('admin.api.gacha');

    # ガチャのランク情報の取得
    Route::post('admin/api/gacha/ranks/{gacha}',
    [Controllers\AdminApiGatyaController::class, 'ranks'])
    ->name('admin.api.gacha.ranks');

    # ガチャランク別、ガチャ商品情報の取得
    Route::post('admin/api/gacha/ranks/gacha_prizes/{discription?}',
    [Controllers\AdminApiGatyaController::class, 'ranks_gacha_prizes'])
    ->name('admin.api.gacha.ranks_gacha_prizes');


});
Route::middleware(['admin_auth'])->group(function () {

    # 商品一覧情報の取得
    Route::post('admin/api/prize',
    [Controllers\AdminApiPrizeController::class, 'index'])
    ->name('admin.api.prize');

    # 商品一覧情報の更新
    Route::patch('admin/api/prize/update/{prize?}',
    [Controllers\AdminApiPrizeController::class, 'update'])
    ->name('admin.api.prize.update');

    # 商品一覧情報のコピー
    Route::post('admin/api/prize/copy/{prize?}',
    [Controllers\AdminApiPrizeController::class, 'copy'])
    ->name('admin.api.prize.copy');

    # 商品情報の削除
    Route::delete('admin/api/prize/destroy/{prize?}',
    [Controllers\AdminApiPrizeController::class, 'destroy'])
    ->name('admin.api.prize.destroy');


});
Route::middleware(['admin_auth'])->group(function () {

    # チケット交換商品の取得
    Route::post('admin/api/ticket_store',
    [Controllers\AdminApiTicketStoreController::class, 'index'])
    ->name('admin.api.ticket_store');

    # チケット交換商品の新規作成
    Route::post('admin/api/ticket_store/create',
    [Controllers\AdminApiTicketStoreController::class, 'create'])
    ->name('admin.api.ticket_store.create');

    # チケット交換商品の更新
    Route::patch('admin/api/ticket_store/update/{store?}',
    [Controllers\AdminApiTicketStoreController::class, 'update'])
    ->name('admin.api.ticket_store.update');

    # チケット交換商品の削除
    Route::delete('admin/api/ticket_store/destroy/{store?}',
    [Controllers\AdminApiTicketStoreController::class, 'destroy'])
    ->name('admin.api.ticket_store.destroy');


});
Route::middleware(['admin_auth'])->group(function () {

    # メール・送信
    Route::post('admin/api/infomation/{infomation}',
    [Controllers\AdminInfomationController::class, 'api_email_post'])
    ->name('admin.api.infomation.email_post');


});
Route::middleware(['admin_auth'])->group(function () {

    # 登録ユーザー[一覧]API
    Route::post('/admmin/api/user',
    [Controllers\AdminApiUserController::class, 'index'])
    ->name('api.admin.user');


});
