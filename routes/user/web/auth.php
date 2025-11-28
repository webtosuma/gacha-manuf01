<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| 認証・登録・パスワード変更
|  UserController
|--------------------------------------------------------------------------
*/
Auth::routes();

# ログイン
Route::get('/login',
[Controllers\Auth\LoginController::class, 'index'])
->name('login');

# 会員登録
Route::get('/register',
[Controllers\Auth\RegisterController::class, 'index'])
->name('register');

# 会員登録処理
Route::post('register/post', //canpaing_introductory_key:紹介キャンペーン キー
[Controllers\Auth\RegisterController::class, 'register_post'])
->name('register.post');

# 会員登録完了
Route::get('register/comp',
[Controllers\Auth\RegisterController::class, 'comp'])
->name('register.comp');


# ログインが必要ですページ(require_login)　
// ※ログイン前にログインが必要なページにアクセスした際に表示されるページ
Route::get('/require_login', function () { return view('auth.require_login'); })
->name('require_login');

# パスワード変更
Route::get('/password/reset',
[Controllers\UserController::class, 'password_reset'])
->name('password.request');

# パスワード変更API ステップ01(reset_pass_step01)
Route::post('reset_pass_step01',
[Controllers\UserController::class, 'reset_pass_step01'])
->name('reset_pass_step01');

# パスワード変更API ステップ02(reset_pass_step02)
Route::post('reset_pass_step02',
[Controllers\UserController::class, 'reset_pass_step02'])
->name('reset_pass_step02');


# 退会処理(destroy)
Route::delete('auth/destroy',
[Controllers\UserController::class,'destroy'])
->middleware(['auth'])
->name('auth.destroy');

# 退会完了ページの表示(completed_destroy)
Route::get('auth/completed_destroy',
function () { return view('auth.completed_destroy'); })
->name('auth.completed_destroy');        //
