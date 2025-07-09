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

    # 管理者ページがEC仕様か
    'admin' => env('STORE_ADMIN', false),

    # 管理者ページでガチャページを利用しない
    'no_gacha' => env('STORE_NO_GACHA', false),

];
