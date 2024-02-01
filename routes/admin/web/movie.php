<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 演出動画
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/movie',
    [Controllers\AdminMovieController::class, 'index'])
    ->name('admin.movie');

    # 表示
    Route::get('/admin/movie/show/{movie}',
    [Controllers\AdminMovieController::class,'show'])
    ->name('admin.movie.show');

    # 新規登録
    Route::get('/admin/movie/create',
    [Controllers\AdminMovieController::class, 'create'])
    ->name('admin.movie.create');

        # 登録
        Route::post('/admin/movie/store',
        [Controllers\AdminMovieController::class, 'store'])
        ->name('admin.movie.store');

    # 基本情報の編集
    Route::get('/admin/movie/edit/{movie}',
    [Controllers\AdminMovieController::class, 'edit'])
    ->name('admin.movie.edit');

        # 基本情報の更新
        Route::patch('/admin/movie/update/{movie}',
        [Controllers\AdminMovieController::class, 'update'])
        ->name('admin.movie.update');

    # 削除
    Route::delete('/admin/movie/destroy/{movie}',
    [Controllers\AdminMovieController::class, 'destroy'])
    ->name('admin.movie.destroy');


});//end middleware
