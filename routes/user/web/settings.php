<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| ユーザー設定
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','user_rank'])->group(function () {

    Route::get('settings',
    function () { return view('settings.index'); })
    ->name('settings');


    /* フォームの表示 */

        # アカウント設定(acount)
        Route::get('/settings/acount',
        function () { return  view('settings.acount.index'); })
        ->name('settings.acount');

        # クレジット情報設定(credit_card)
        Route::get('/settings/credit_card',
        [Controllers\SettingsController::class, 'credit_card'])
        ->name('settings.credit_card');

        # 商品発送先の住所設定(shipped_address)
        Route::get('/settings/shipped_address',
        function () { return  view('settings.shipped_address'); })
        ->name('settings.shipped_address');

        # メール受信設定(email_reception) 未使用
        Route::get('/settings/email_reception',
        function () { return  view('settings.email_reception'); })
        ->name('settings.email_reception');

        # 退会の手続き(withdraw)
        Route::get('/settings/withdraw',
        function () { return  view('settings.withdraw'); })
        ->name('settings.withdraw');


    /* 更新処理 */

        # アカウント情報変更(acount_update)
        Route::patch('/settings/acount/update',
        [Controllers\SettingsController::class, 'acount_update'])
        ->name('settings.acount.update');


        # クレジット情報・新規登録
        Route::post('/settings/credit_card/create',
        [Controllers\SettingsController::class, 'credit_card_create'])
        ->name('settings.credit_card.create');

        # クレジット情報・削除
        Route::delete('/settings/credit_card/destroy',
        [Controllers\SettingsController::class, 'credit_card_destroy'])
        ->name('settings.credit_card.destroy');


        # メール受信設定(email_reception_update)
        Route::patch('/settings/email_reception/update',
        [Controllers\SettingsController::class, 'email_reception_update'])
        ->name('settings.email_reception.update');


    /* ユーザー誕生日登録 */

        # 誕生日入力フォーム
        Route::get('/settings/age/birthday',
        function () { return  view('settings.age.birthday'); })
        ->name('settings.age.birthday');

        # 誕生日更新
        Route::patch('/settings/age/birthday/update',
        [Controllers\SettingsController::class, 'birthday_update'])
        ->name('settings.age.birthday.update');

        # 誕生日入力フォーム完了
        Route::get('/settings/age/birthday/comp',
        function () { return  view('settings.age.birthday_comp'); })
        ->name('settings.age.birthday.comp');

        # 年齢制限があります
        Route::get('/settings/age/restrictedy',
        function () { return  view('settings.age.restrictedy'); })
        ->name('settings.age.restrictedy');


    /**/
});
