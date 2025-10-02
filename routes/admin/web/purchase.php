<?php
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Admin - 買取表
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {

    # 一覧
    Route::get('/admin/purchase',
    [Controllers\AdminPurchaseController ::class, 'index'])
    ->name('admin.purchase');

    # 新規登録
    Route::get('/admin/purchase/create',
    [Controllers\AdminPurchaseController::class, 'create'])
    ->name('admin.purchase.create');

        # 登録
        Route::post('/admin/purchase/store',
        [Controllers\AdminPurchaseController ::class, 'store'])
        ->name('admin.purchase.store');


});//end middleware
/*
|--------------------------------------------------------------------------
| Admin - 買取表 API
|--------------------------------------------------------------------------
*/
Route::middleware(['admin_auth'])->group(function () {


    # 一覧情報の発行API(admin_list)
    Route::post('/admmin/api/purchase/',
    [Controllers\AdminApiPurchaseController::class, 'index'])
    ->name('admin.api.purchase');

    # 更新
    Route::patch('admin/api/purchase/update',
    [Controllers\AdminApiPurchaseController::class, 'update'])
    ->name('admin.api.purchase.update');


});//end middleware
/*
|--------------------------------------------------------------------------
| Admin - 買取表 API ガチャ商品の登録 ルーティング
|--------------------------------------------------------------------------
 */
Route::middleware(['admin_auth'])->group(function () {

    # 商品一覧情報の取得
    Route::post('admin/api/purchase/prize',
    [Controllers\AdminApiPurchaseController::class, 'prize'])
    ->name('admin.api.purchase.prize');


});
