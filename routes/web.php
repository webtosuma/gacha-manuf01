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


# ストアー
if( true )
{
    include('manuf/web.php');
}
