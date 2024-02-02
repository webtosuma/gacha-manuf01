<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ガチャ(詳細情報)
| AdminGachaDisriptionController
| AdminGachaPrizeController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # ガチャの詳細情報
    Route::get('/admin/gacha/prize/{gacha}',function(\App\Models\Gacha $gacha) {
        return view('admin.gacha.prize.show', compact('gacha'));
    })->name('admin.gacha.prize.show');

    # 商品の編集
    Route::get('/admin/gacha/prize/edit/{gacha}',
    [Controllers\AdminGachaPrizeController ::class, 'edit'])
    ->name('admin.gacha.prize.edit');

        # 商品の更新
        Route::patch('/admin/gacha/prize/update/{gacha}',
        [Controllers\AdminGachaPrizeController ::class, 'update'])
        ->name('admin.gacha.prize.update');

    // # 商品残数の編集
    // Route::get('/admin/gacha/prize/edit/{gacha}',
    // [Controllers\AdminGachaPrizeController ::class, 'edit'])
    // ->name('admin.gacha.prize.edit');




    # 演出動画情報の編集
    Route::get('/admin/gacha/movie/edit/{gacha}',
    [Controllers\AdminGachaMovieController ::class, 'edit'])
    ->name('admin.gacha.movie.edit');

        # 演出動画情報の更新
        Route::patch('/admin/gacha/movie/update/{gacha}',
        [Controllers\AdminGachaMovieController ::class, 'update'])
        ->name('admin.gacha.movie.update');

    # 詳細情報の編集
    Route::get('/admin/gacha/discription/edit/{gacha}',
    [Controllers\AdminGachaDisriptionController ::class, 'edit'])
    ->name('admin.gacha.discription.edit');

        # 詳細情報の更新
        Route::patch('/admin/gacha/discription/update/{gacha}',
        [Controllers\AdminGachaDisriptionController ::class, 'update'])
        ->name('admin.gacha.discription.update');



});//end middleware
