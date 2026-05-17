<?php
/*
==========================================================================
 Manufacturer 製造業ガチャ
==========================================================================
*/
return [

    # Manufガチャの利用
    'app' => (bool) env('MANUF_APP', false),


    #　購入
    'purchase' => [

        ## 待機のタイムアウト時間(デフォルト:30分)
        // ManufGachaTitleMachineモデルのアクセサーにて利用
        'pending_timeout' => (int) env('MANUF_PURCHASE_PENDING_TIMEOUT', 1800),

        ## 待機のタイムアウト時間(デフォルト:30分)
        'max_playcount'   => (int) env('MANUF_PURCHASE_MAX_PLAYCOUNT', 100),

    ],


    # 発送設定
    'shipped' => [

        ## 発送料金(数値)
        'fee' => (int)  env('MANUF_SHIPPED_FEE',0),

        ## 商品数n個ごとに発送数を加算(数値)
        'item_count_unit'   => (int) 0,

    ],
    
];
