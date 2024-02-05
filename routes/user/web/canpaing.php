<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| キャンペーン
|--------------------------------------------------------------------------
*/
# 会員登録:紹介キャンペーン
Route::get('register/ci/{key}', //key:紹介キャンペーン キー
[Controllers\CanpaingIntroductoryController::class, 'register'])
->name('canpaing.introductory.register');


# お友達紹介キャンペーンURL(canpaing_introductory)
Route::get('/canpaing/introductory',
[Controllers\CanpaingIntroductoryController::class, 'index'])
->name('canpaing.introductory');




