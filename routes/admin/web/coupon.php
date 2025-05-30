<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin - クーポン
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/coupon',
    [Controllers\AdminCouponController ::class, 'index'])
    ->name('admin.coupon');

    # 履歴
    Route::get('/admin/coupon/history',
    [Controllers\AdminCouponController ::class, 'history'])
    ->name('admin.coupon.history');


    # 新規登録
    Route::get('/admin/coupon/create',
    [Controllers\AdminCouponController::class, 'create'])
    ->name('admin.coupon.create');

        # 登録
        Route::post('/admin/coupon/store',
        [Controllers\AdminCouponController ::class, 'store'])
        ->name('admin.coupon.store');

    # 基本情報の編集
    Route::get('/admin/coupon/edit/{coupon}',
    [Controllers\AdminCouponController ::class, 'edit'])
    ->name('admin.coupon.edit');

        # 基本情報の更新
        Route::patch('/admin/coupon/update/{coupon}',
        [Controllers\AdminCouponController ::class, 'update'])
        ->name('admin.coupon.update');

    # 削除
    Route::delete('/admin/coupon/destroy/{coupon}',
    [Controllers\AdminCouponController ::class, 'destroy'])
    ->name('admin.coupon.destroy');

    # コピー
    Route::post('/admin/coupon/copy/{coupon}',
    [Controllers\AdminCouponController ::class, 'copy'])
    ->name('admin.coupon.copy');


});//end middleware
/*
|--------------------------------------------------------------------------
| Admin - クーポン API
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 一覧情報の発行API(admin_list)
    Route::post('/admmin/api/coupon/',
    [Controllers\AdminApiCouponController::class, 'index'])
    ->name('admin.api.coupon');


    # 履歴一覧情報の発行API(admin_list)
    Route::post('/admmin/api/coupon/history',
    [Controllers\AdminApiCouponHistoryController::class, 'history'])
    ->name('admin.api.coupon.history');



});//end middleware
