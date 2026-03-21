<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manuf;
/*
|--------------------------------------------------------------------------
| Manufacturer:管理者(Admin) ガチャタイトル01(基本情報)
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # ガチャ一覧//通常用を非表示
    Route::get('/admin/gacha/l/{category_code?}',
    function(){ return \App::abort(404); })
    ->name('admin.gacha');


    # ガチャ タイトル一覧
    Route::get('/admin/gacha_title',
    [Manuf\AdminGachaTitleController ::class, 'index'])
    ->name('admin.gacha_title');


    # API ガチャタイトル一覧の取得
    Route::post('admin/api/gacha_title',
    [Manuf\AdminApiGatyaTitleController::class, 'index'])
    ->name('admin.api.gacha_title');



    # 詳細
    Route::get('/admin/gacha_title/show/{gacha_title}',
    [Manuf\AdminGachaTitleController ::class, 'show'])
    ->name('admin.gacha_title.show');


    # 新規登録
    Route::get('/admin/gacha_title/create/{category_code?}',
    [Manuf\AdminGachaTitleController ::class, 'create'])
    ->name('admin.gacha_title.create');

        # 登録
        Route::post('/admin/gacha_title/store',
        [Manuf\AdminGachaTitleController ::class, 'store'])
        ->name('admin.gacha_title.store');

    # 基本情報の編集
    Route::get('/admin/gacha_title/edit/{gacha_title}',
    [Manuf\AdminGachaTitleController ::class, 'edit'])
    ->name('admin.gacha_title.edit');

        # 基本情報の更新
        Route::patch('/admin/gacha_title/update/{gacha_title}',
        [Manuf\AdminGachaTitleController ::class, 'update'])
        ->name('admin.gacha_title.update');

    # 削除
    Route::delete('/admin/gacha_title/destroy/{gacha_title}',
    [Manuf\AdminGachaTitleController ::class, 'destroy'])
    ->name('admin.gacha_title.destroy');


});//end middleware
