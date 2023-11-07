<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

include('_admin.php');


/*
|--------------------------------------------------------------------------
| 認証・登録・パスワード変更
|--------------------------------------------------------------------------
*/
    // # ユーザー登録画面の表示(register_form)
    // Route::get('/worker_auth/register_form/{affiliate_key?}',
    // function( $affiliate_key='' ){ return view('worker_auth.register_form',['step'=>1, 'affiliate_key'=>$affiliate_key, ] ); })
    // ->name('worker_auth.register_form');

    //     # ユーザー登録 ステップ01(register_step01)
    //     Route::post('/worker_auth/register_step01', [Controllers\WorkerAuthController::class, 'register_step01'])
    //     ->name('api.worker_auth.register_step01');

    //     # ユーザー登録 ステップ02(register_step02)
    //     Route::post('/worker_auth/register_step02', [Controllers\WorkerAuthController::class, 'register_step02'])
    //     ->name('api.worker_auth.register_step02');

    //     # ユーザー登録 ステップ03(register_step03)
    //     Route::post('/worker_auth/register_step03', [Controllers\WorkerAuthController::class, 'register_step03'])
    //     ->name('api.worker_auth.register_step03');

    //     # ユーザー登録 ステップ04(register_step04)
    //     Route::post('/worker_auth/register_step04', [Controllers\WorkerAuthController::class, 'register_step04'])
    //     ->name('api.worker_auth.register_step04');



    # ログインが必要ですページ(require_login)　※ログイン前にログインが必要なページにアクセスした際に表示されるページ
    Route::get('/require_login', function () { return view('auth.require_login'); })
    ->name('require_login');


Route::get('/', function () { return view('home'); });

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {

    Route::get( 'payment', [Controllers\PaymentController::class, 'index'])->name('payment');
    Route::post('payment', [Controllers\PaymentController::class, 'payment']);

});

