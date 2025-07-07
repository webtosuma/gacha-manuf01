<?php
/*
|--------------------------------------------------------------------------
| ECサイト関係　設定
|--------------------------------------------------------------------------
|
|
*/
return [

    # ストアー商品の比率
    'item_ratio' => env('STORE_ITEM_RATIO', 'ratio-1x1'),


    # ガチャサイトURL
    'r_gacha'    => env('STORE_ROUTE_GACHA', null),
    'r_store'    => env('STORE_ROUTE_STORE', null),

];
