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
    Route::get('/admin/gacha/title',
    [Manuf\AdminGachaTitleController ::class, 'index'])
    ->name('admin.gacha.title');


    # API ガチャタイトル一覧の取得
    Route::post('admin/api/gacha/title',
    [Manuf\AdminApiGatyaTitleController::class, 'index'])
    ->name('admin.api.gacha.title');



    # 詳細
    Route::get('/admin/gacha/title/show/{gacha_title}',
    [Manuf\AdminGachaTitleController ::class, 'show'])
    ->name('admin.gacha.title.show');


    # 新規登録
    Route::get('/admin/gacha/title/create/{category_code?}',
    [Manuf\AdminGachaTitleController ::class, 'create'])
    ->name('admin.gacha.title.create');

        # 登録
        Route::post('/admin/gacha/title/store',
        [Manuf\AdminGachaTitleController ::class, 'store'])
        ->name('admin.gacha.title.store');

    # 基本情報の編集
    Route::get('/admin/gacha/title/edit/{gacha_title}',
    [Manuf\AdminGachaTitleController ::class, 'edit'])
    ->name('admin.gacha.title.edit');

        # 基本情報の更新
        Route::patch('/admin/gacha/title/update/{gacha_title}',
        [Manuf\AdminGachaTitleController ::class, 'update'])
        ->name('admin.gacha.title.update');

    # 削除
    Route::delete('/admin/gacha/title/destroy/{gacha_title}',
    [Manuf\AdminGachaTitleController ::class, 'destroy'])
    ->name('admin.gacha.title.destroy');


});//end middleware
