<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;



include('admin/api.php');

include('admin/web.php');

include('user/api.php');

include('user/web.php');//


# ストアー
if( config('store.admin') )
{
    include('store/web.php');
}


# Manufガチャ
if( config('manuf.app') )
{
    include('manuf/web.php');
}
