<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin スポンサー 広告 AdminSponsorAdController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 一覧
    Route::get('/admin/sponsor_ad',
    [Controllers\AdminSponsorAdController::class, 'index'])
    ->name('admin.sponsor_ad');

    # 表示
    Route::get('/admin/sponsor_ad/show/{sponsor_ad}',
    [Controllers\AdminSponsorAdController::class,'show'])
    ->name('admin.sponsor_ad.show');

    # 新規登録
    Route::get('/admin/sponsor_ad/create',
    [Controllers\AdminSponsorAdController::class, 'create'])
    ->name('admin.sponsor_ad.create');

        # 登録
        Route::post('/admin/sponsor_ad/store',
        [Controllers\AdminSponsorAdController::class, 'store'])
        ->name('admin.sponsor_ad.store');

    # 編集
    Route::get('/admin/sponsor_ad/edit/{sponsor_ad}',
    [Controllers\AdminSponsorAdController::class, 'edit'])
    ->name('admin.sponsor_ad.edit');

        # 更新
        Route::patch('/admin/sponsor_ad/update/{sponsor_ad}',
        [Controllers\AdminSponsorAdController::class, 'update'])
        ->name('admin.sponsor_ad.update');

    # 削除
    Route::delete('/admin/sponsor_ad/destroy/{sponsor_ad}',
    [Controllers\AdminSponsorAdController::class, 'destroy'])
    ->name('admin.sponsor_ad.destroy');


});//end middleware
