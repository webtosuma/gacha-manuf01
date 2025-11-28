<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 二段階認証ログイン(TFA)Two-factor authentication
|--------------------------------------------------------------------------
*/



 /*
|--------------------------------------------------------------------------
| API 二段階認証ログイン(TFA)
|--------------------------------------------------------------------------
*/

    # パスワード認証 API
    Route::post('/auth/api/loguin/password',
    [Controllers\Auth\TfaController::class, 'api_login_password'])
    ->name('auth.api.login.password');

    # メール認証 API
    Route::post('/auth/api/loguin/tfa_key',
    [Controllers\Auth\TfaController::class, 'api_login_tfa_key'])
    ->name('auth.api.login.tfa_key');


/* ~ */
