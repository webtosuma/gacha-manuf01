<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;


Route::get('/', function () { return view('home'); });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get( 'payment', [Controllers\PaymentController::class, 'index'])->name('payment');
Route::post('payment', [Controllers\PaymentController::class, 'payment']);
