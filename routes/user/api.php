<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| ユーザー登録
|--------------------------------------------------------------------------
*/

    # ユーザー登録(step01) API
    Route::post('/register/api/step01',
    [Controllers\Auth\RegisterController::class, 'step01'])
    ->name('api.register.step01');

/*
|--------------------------------------------------------------------------
| パスワード変更
|--------------------------------------------------------------------------
*/
    # パスワード変更API ステップ01(reset_pass_step01)
    Route::post('reset_pass_step01/api',
    [Controllers\UserController::class, 'reset_pass_step01'])
    ->name('reset_pass_step01');

    # パスワード変更API ステップ02(reset_pass_step02)
    Route::post('user/reset_pass_step02/api',
    [Controllers\UserController::class, 'reset_pass_step02'])
    ->name('reset_pass_step02');


/*
|--------------------------------------------------------------------------
| お問い合わせ
|--------------------------------------------------------------------------
*/

    # お問い合わせ[バリデーション]API(component_data_api)
    Route::post('/contact/api/validation',
    [Controllers\ContactController::class, 'validation'])
    ->name('api.contact.validation');

    # お問い合わせ[完了]API(completion_api)
    Route::post('contact/api/completion',
    [Controllers\ContactController::class, 'completion'])
    ->name('api.contact.completion');


/*
|--------------------------------------------------------------------------
| 取得した商品
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        # ユーザーの取得積み商品
        //（ポイント交換・発送済みを除く）
        Route::post('user_prize/api',
        [Controllers\UserPrizeApiController::class, 'index'])
        ->name('api_user_prize');

        # IDを指定して、ユーザーの取得積み商品取得
        //（ポイント交換・発送済みを除く）
        Route::post('user_prize/find/api',
        [Controllers\UserPrizeApiController::class, 'find'])
        ->name('api.user_prize.find');

    });

/*
|--------------------------------------------------------------------------
| チケット交換商品
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        # 一覧
        Route::post('ticket_store/api',
        [Controllers\TicketStoreApiController::class, 'index'])
        ->name('api.ticket_store');

    });




/*
|--------------------------------------------------------------------------
| ガチャ履歴
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {

        # ユーザーのガチャ履歴に紐づいた、ユーザーの取得積み商品
        Route::post('use_gacha_history/{user_gacha_history}/api',
        [Controllers\UserGachaHistoryApiContloller::class, 'show'])
        ->name('api.use_gacha_history.show');

    });



/*
|--------------------------------------------------------------------------
| ユーザーアドレス
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])->group(function () {


        # ユーザーアドレスの取得
        Route::post('use_address/api',
        [Controllers\UserAddressApiController::class, 'index'])
        ->name('api.use_address');

        # ユーザーアドレスの保存
        Route::post('use_address/store/api',
        [Controllers\UserAddressApiController::class, 'store'])
        ->name('api.use_address.store');

        # ユーザーアドレスの削除
        Route::delete('use_address/destroy/api/{user_address?}',
        [Controllers\UserAddressApiController::class, 'destroy'])
        ->name('api.use_address.destroy');

    });

