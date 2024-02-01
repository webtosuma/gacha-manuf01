<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 管理者設定
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 管理者一覧の表示(register)
    Route::get('admin/register',
    [App\Http\Controllers\AdminController::class,'index'])
    ->name('admin.register');

    # 管理者登録画面の表示(create)
    Route::get('admin/register/create',
    function(){ return view('admin.register.create'); })
    ->name('admin.register.create');

    # 管理者登録処理(register_post)
    Route::post('admin/register/store',
    [App\Http\Controllers\AdminController::class,'store'])
    ->name('admin.register.store');

    # 管理者情報編集ページの表示(register_edit)
    Route::get('admin/register/edit/{admin}',
    [App\Http\Controllers\AdminController::class,'edit'])
    ->name('admin.register.edit');

    # 管理者情報の更新(register_update)
    Route::patch('admin/register/update/{admin}',
    [App\Http\Controllers\AdminController::class,'update'])
    ->name('admin.register.update');

    # 管理者情報の削除(register_destroy)
    Route::delete('admin/register/destroy/{admin}',
    [App\Http\Controllers\AdminController::class,'destroy'])
    ->name('admin.register.destroy');

});//end middleware
