<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
|--------------------------------------------------------------------------
| Manufacturer:管理者(Admin) ガチャタイトル　筺体
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/gacha_title/{gacha_title}/machine',
    [Manuf\AdminGachaTitleMachineController ::class, 'index'])
    ->name('admin.gacha_title.machine');


    # 詳細
    Route::get('/admin/gacha_title/{gacha_title}/machine/show/{machine}',
    [Manuf\AdminGachaTitleMachineController ::class, 'show'])
    ->name('admin.gacha_title.machine.show');


    # 新規登録
    Route::get('/admin/gacha_title/{gacha_title}/machine/create',
    [Manuf\AdminGachaTitleMachineController ::class, 'create'])
    ->name('admin.gacha_title.machine.create');

        # 登録
        Route::post('/admin/gacha_title/{gacha_title}/machine/store',
        [Manuf\AdminGachaTitleMachineController ::class, 'store'])
        ->name('admin.gacha_title.machine.store');

    # 基本情報の編集
    Route::get('/admin/gacha_title/{gacha_title}/machine/edit/{machine}',
    [Manuf\AdminGachaTitleMachineController ::class, 'edit'])
    ->name('admin.gacha_title.machine.edit');

        # 基本情報の更新
        Route::patch('/admin/gacha_title/{gacha_title}/machine/update/{machine}',
        [Manuf\AdminGachaTitleMachineController ::class, 'update'])
        ->name('admin.gacha_title.machine.update');

    # 削除
    Route::delete('/admin/gacha_title/{gacha_title}/machine/destroy/{machine}',
    [Manuf\AdminGachaTitleMachineController ::class, 'destroy'])
    ->name('admin.gacha_title.machine.destroy');

    # コピー
    Route::post('/admin/gacha_title/{gacha_title}/machine/copy/{machine}',
    [Manuf\AdminGachaTitleMachineController ::class, 'copy'])
    ->name('admin.gacha_title.machine.copy');

});//end middleware
