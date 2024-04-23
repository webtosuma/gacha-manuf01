<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin スポンサー  AdminSponsorController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 一覧
    Route::get('/admin/sponsor',
    [Controllers\AdminSponsorController::class, 'index'])
    ->name('admin.sponsor');

    # 表示
    Route::get('/admin/sponsor/show/{sponsor}',
    [Controllers\AdminSponsorController::class,'show'])
    ->name('admin.sponsor.show');

    # 新規登録
    Route::get('/admin/sponsor/create',
    [Controllers\AdminSponsorController::class, 'create'])
    ->name('admin.sponsor.create');

        # 登録
        Route::post('/admin/sponsor/store',
        [Controllers\AdminSponsorController::class, 'store'])
        ->name('admin.sponsor.store');

    # 編集
    Route::get('/admin/sponsor/edit/{sponsor}',
    [Controllers\AdminSponsorController::class, 'edit'])
    ->name('admin.sponsor.edit');

        # 更新
        Route::patch('/admin/sponsor/update/{sponsor}',
        [Controllers\AdminSponsorController::class, 'update'])
        ->name('admin.sponsor.update');

    # 削除
    Route::delete('/admin/sponsor/destroy/{sponsor}',
    [Controllers\AdminSponsorController::class, 'destroy'])
    ->name('admin.sponsor.destroy');


});//end middleware
