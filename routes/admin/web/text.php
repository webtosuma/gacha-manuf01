<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin 文書設定
|--------------------------------------------------------------------------
*/

# 一覧
Route::get('/admin/text',
[Controllers\AdminTextController ::class, 'index'])
->name('admin.text');


    # 会員ランク編集
    Route::get('/admin/text/user_rank/edit',
    [Controllers\AdminTextUserRankController ::class, 'edit'])
    ->name('admin.text.user_rank.edit');

    # 会員ランク 更新
    Route::patch('/admin/text/user_rank/update',
    [Controllers\AdminTextUserRankController ::class, 'update'])
    ->name('admin.text.user_rank.update');



    # 編集
    Route::get('/admin/text/{type}/edit',
    [Controllers\AdminTextController ::class, 'edit'])
    ->name('admin.text.edit');


    # メタ情報 更新
    Route::patch('/admin/text/meta/update',
    [Controllers\AdminTextController ::class, 'meta_update'])
    ->name('admin.text.meta.update');

    # 古物商営業許可 更新
    Route::patch('/admin/text/sbg_license/update',
    [Controllers\AdminTextController ::class, 'sbg_license_update'])
    ->name('admin.text.sbg_license.update');

    # 通常　更新
    Route::patch('/admin/text/{type}/update',
    [Controllers\AdminTextController ::class, 'update'])
    ->name('admin.text.update');



# 複数登録　一覧　(multiple)
Route::get('/admin/text/multiple/{type}',
[Controllers\AdminTextMultipleController ::class, 'index'])
->name('admin.text.multiple');

    # 複数登録　新規作成
    Route::get('/admin/text/multiple/{type}/create',
    [Controllers\AdminTextMultipleController ::class, 'create'])
    ->name('admin.text.multiple.create');

        # 複数登録　登録
        Route::post('/admin/text/multiple/{type}/store',
        [Controllers\AdminTextMultipleController ::class, 'store'])
        ->name('admin.text.multiple.store');


    # 複数登録　編集
    Route::get('/admin/text/multiple/{type}/edit/{text}',
    [Controllers\AdminTextMultipleController ::class, 'edit'])
    ->name('admin.text.multiple.edit');

        # 複数登録　更新
        Route::patch('/admin/text/multiple/{type}/update/{text}',
        [Controllers\AdminTextMultipleController ::class, 'update'])
        ->name('admin.text.multiple.update');


    # 複数登録　削除
    Route::delete('/admin/text/multiple/{type}/destroy/{text}',
    [Controllers\AdminTextMultipleController ::class, 'destroy'])
    ->name('admin.text.multiple.destroy');

//
