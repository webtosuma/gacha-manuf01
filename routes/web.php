<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

    Route::get('/', function(){
        return redirect('https://cardfesta.jp');
    } )->name('home');

    Route::get('/g/{any?}',function(){

        return redirect('https://cardfesta.jp');

    })->where('any', '.*')
    ->name('gacha_category');


    include('admin/api.php');

    include('admin/web.php');

    include('user/api.php');

    include('user/web.php');//



    // Auth::routes();

    // # ONLINE
    // Route::get('/{any?}', function()  {

    //     return redirect('https://cardfesta.jp/');



    // })->where('any', '.*');

