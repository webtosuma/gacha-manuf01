<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin サブスクプラン AdminSubscriptionController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/subscription',
    [Controllers\AdminSubscriptionController::class, 'index'])
    ->name('admin.subscription');

    # 契約中ユーザー
    Route::get('/admin/subscription/current_user/{subscription}',
    [Controllers\AdminSubscriptionController::class,'current_user'])
    ->name('admin.subscription.current_user');

    # 新規登録
    Route::get('/admin/subscription/create',
    [Controllers\AdminSubscriptionController::class, 'create'])
    ->name('admin.subscription.create');

        # 登録
        Route::post('/admin/subscription/store',
        [Controllers\AdminSubscriptionController::class, 'store'])
        ->name('admin.subscription.store');

    # 基本情報の編集
    Route::get('/admin/subscription/edit/{subscription}',
    [Controllers\AdminSubscriptionController::class, 'edit'])
    ->name('admin.subscription.edit');

        # 基本情報の更新
        Route::patch('/admin/subscription/update/{subscription}',
        [Controllers\AdminSubscriptionController::class, 'update'])
        ->name('admin.subscription.update');

    # 削除
    Route::delete('/admin/subscription/destroy/{subscription}',
    [Controllers\AdminSubscriptionController::class, 'destroy'])
    ->name('admin.subscription.destroy');


});//end middleware
