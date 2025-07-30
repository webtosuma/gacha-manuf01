<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| お問い合わせ AdminContactController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    Route::get('/admin/contact',
    [Controllers\AdminContactController::class, 'index'])
    ->name('admin.contact');




    # お問い合わせ[一覧情報の発行]API(admin_list)
    Route::post('/admmin/api/contact/list',
    [Controllers\AdminContactController::class, 'admin_list'])
    ->name('api.admin.contact.list');


    # お問い合わせ[対応済変更]API(admin_responsed)
    Route::patch('/admmin/api/contact/responsed/{contact?}',
    [Controllers\AdminContactController::class, 'admin_responsed'])
    ->name('api.admin.contact.responsed');

    # お問い合わせ[削除]API(admin_destroy)
    Route::delete('/admmin/api/contact/destroy/{contact?}',
    [Controllers\AdminContactController::class, 'admin_destroy'])
    ->name('api.admin.contact.destroy');

    # お問い合わせ[フォルダの作成]API(admin_type_create)
    Route::post('/admmin/api/contact/type_create',
    [Controllers\AdminContactController::class, 'admin_type_create'])
    ->name('api.admin.contact.type_create');

    # CSVファイルのダウンロード
    Route::get('/admin/contact/dl_csv',
    [Controllers\AdminContactController::class, 'dl_csv'])
    ->name('admin.contact.dl_csv');


});//end middleware
