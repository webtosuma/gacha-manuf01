<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| お知らせ
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/infomation',
    [Controllers\AdminInfomationController::class,'index'])
    ->name('admin.infomation');

    # 表示
    Route::get('/admin/infomation/show/{infomation}',
    [Controllers\AdminInfomationController::class,'show'])
    ->name('admin.infomation.show');

    # 新規登録
    Route::get('/admin/infomation/create',
    [Controllers\AdminInfomationController::class, 'create'])
    ->name('admin.infomation.create');

        # 登録
        Route::post('/admin/infomation/store',
        [Controllers\AdminInfomationController ::class, 'store'])
        ->name('admin.infomation.store');

    # 基本情報の編集
    Route::get('/admin/infomation/edit/{infomation}',
    [Controllers\AdminInfomationController ::class, 'edit'])
    ->name('admin.infomation.edit');

        # 基本情報の更新
        Route::patch('/admin/infomation/update/{infomation}',
        [Controllers\AdminInfomationController ::class, 'update'])
        ->name('admin.infomation.update');

    # 削除
    Route::delete('/admin/infomation/destroy/{infomation}',
    [Controllers\AdminInfomationController ::class, 'destroy'])
    ->name('admin.infomation.destroy');


    # メール・プレビュー
    Route::get('/admin/infomation/email/{infomation}',
    [Controllers\AdminInfomationController ::class, 'email'])
    ->name('admin.infomation.email');

    # メール・送信完了
    Route::get('/admin/infomation/comp_email_post/{infomation}',
    [Controllers\AdminInfomationController ::class, 'comp_email_post'])
    ->name('admin.infomation.comp_email_post');

});//end middleware

