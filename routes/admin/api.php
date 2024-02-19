<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;



Route::middleware(['admin_auth'])->group(function () {

    # ガチャカテゴリー情報の取得
    Route::post('admin/api/gacha/category',
    [Controllers\AdminApiGatyaController::class, 'category'])
    ->name('admin.api.gacha.category');

    # ガチャのランク情報の取得
    Route::post('admin/api/gacha/ranks/{gacha}',
    [Controllers\AdminApiGatyaController::class, 'ranks'])
    ->name('admin.api.gacha.ranks');

    # ガチャランク別、ガチャ商品情報の取得
    Route::post('admin/api/gacha/ranks/gacha_prizes/{discription?}',
    [Controllers\AdminApiGatyaController::class, 'ranks_gacha_prizes'])
    ->name('admin.api.gacha.ranks_gacha_prizes');



    # 商品一覧情報の取得
    Route::post('admin/api/prize',
    [Controllers\AdminApiPrizeController::class, 'index'])
    ->name('admin.api.prize');

    # 商品一覧情報の更新
    Route::patch('admin/api/prize/update/{prize?}',
    [Controllers\AdminApiPrizeController::class, 'update'])
    ->name('admin.api.prize.update');

    # 商品情報の削除
    Route::delete('admin/api/prize/{prize?}',
    [Controllers\AdminApiPrizeController::class, 'destroy'])
    ->name('admin.api.prize.destroy');


    # メール・送信
    Route::post('admin/api/prize/{infomation}',
    [Controllers\AdminInfomationController::class, 'api_email_post'])
    ->name('admin.api.infomation.email_post');

});

Route::middleware(['admin_auth'])->group(function () {

    # お問い合わせ[一覧情報の発行]API(admin_list)
    Route::post('/admmin/api/contact/list',
    [Controllers\ContactController::class, 'admin_list'])
    ->name('api.admin.contact.list');


    # お問い合わせ[対応済変更]API(admin_responsed)
    Route::patch('/admmin/api/contact/responsed/{contact?}',
    [Controllers\ContactController::class, 'admin_responsed'])
    ->name('api.admin.contact.responsed');

    # お問い合わせ[削除]API(admin_destroy)
    Route::delete('/admmin/api/contact/destroy/{contact?}',
    [Controllers\ContactController::class, 'admin_destroy'])
    ->name('api.admin.contact.destroy');

});
