<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| ガチャ(一覧表示の設定) AdminGachaSettingsController
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # ガチャの設定 編集
    Route::get('/admin/gacha/settings/edit_list',
    [App\Http\Controllers\AdminGachaSettingsController::class, 'edit_list'])
    ->name('admin.gacha.settings.edit_list');


    # レインボー 更新
    Route::patch('/admin/gacha/settings/update_rainbow',
    [Controllers\AdminGachaSettingsController ::class, 'update_rainbow'])
    ->name('admin.text.update_rainbow');

    # ガチャ販売機の画像設定 更新
    Route::patch('/admin/gacha/settings/update_card_image',
    [App\Http\Controllers\AdminGachaSettingsController::class, 'update_card_image'])
    ->name('admin.gacha.settings.update_card_image');

    # ガチャ読み込み中の動画設定 更新
    Route::patch('/admin/gacha/settings/update_loading_movie',
    [App\Http\Controllers\AdminGachaSettingsController::class, 'update_loading_movie'])
    ->name('admin.gacha.settings.update_loading_movie');

    # ガチャの設定 その他 更新
    Route::patch('/admin/gacha/settings/update_other',
    [App\Http\Controllers\AdminGachaSettingsController::class, 'update_other'])
    ->name('admin.gacha.settings.update_other');


});//end middleware
