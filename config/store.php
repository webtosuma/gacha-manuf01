<?php
/*
|--------------------------------------------------------------------------
| ECサイト関係　設定
|--------------------------------------------------------------------------
|
|
*/
return [

    # EC管理者ページの利用
    'admin'      => env('STORE_ADMIN', false),

    # EC商品の比率
    'item_ratio' => env('STORE_ITEM_RATIO', 'ratio-1x1'),

    # ガチャサイトURL
    'r_gacha'    => env('STORE_ROUTE_GACHA', null),
    'r_store'    => env('STORE_ROUTE_STORE', null),


    # 管理者ページでガチャページを利用しない
    'no_gacha'   => env('STORE_NO_GACHA', false),




    /* 発送ポイント(数値) */
    'shipped_point' => (int) env('STORE_SEEPED_POINT', 0),


];
