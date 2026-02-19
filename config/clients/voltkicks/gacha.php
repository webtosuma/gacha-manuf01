<?php
/*
|--------------------------------------------------------------------------
| ガチャの詳細設定
|--------------------------------------------------------------------------
|
| 各クライアントごとのガチャの差異設定をこちらで指定します。
|
*/

    /**
     * ガチャの種類（限定がチャの設定）
     */
        $types = [
            'no_custom'    => '通常',//カスタムボタンなし

            // 'nomal'        => 'カスタムボタン',
            // 'max_custom'   => 'カスタムボタン(上限付き)',
            // 'no_custom'    => 'カスタムボタンなし',

            // 'nomal'        => '100連ボタンあり',
            // 'no_custom'    => '100連ボタンなし',



            'only_new_user'=> '新規会員限定',
            'one_time'     => '1回限定',
            // 'only_oneday'  => '1日1回限定',
            // 'one_chance'   => '1回or10回限定',

            // 'n_time'             => '⚪︎回限定',
            // 'n_time_no_custom'   => '⚪︎回限定(カスタムボタンなし)',
            // 'n_oneday'           => '1日⚪︎回限定',
            // 'n_oneday_no_custom' => '1日⚪︎回限定(カスタムボタンなし)',

        ];

        # イベント用
        if( config('app.event_gacha') ){ $types['event'] ='イベント用'; }


    /**
     * ガチャランク　一覧
     */
        $ranks = [

            100 => 'RankSS',
            173 => 'RankSS P',
            200 => 'RankS',
            273 => 'RankS P',
            300 => 'RankA',
            373 => 'RankA P',

            400 => 'RankB',
            500 => 'RankC',
            600 => 'RankD',

            // 901 => 'シークレット・キリ',
            // 903 => 'シークレット・ピタリ',

            320 => 'ゾロ目',
            310 => 'キリ番',
            // 330 => 'ピタリ賞',

            // 362 => '個人ゾロ目',
            // 361 => '個人キリ番',
            // 363 => '個人ピタリ賞',

            10  => 'ラストワン',

            // 1001  => 'スライド表示',

        ];

    /* ~ */




return [


    /* ガチャの種類（限定がチャの設定）*/
    'types' => $types,


    /* がチャのデフォルトタイプ */
    // 'defaults_type' => 'nomal',    //カスタムボタンあり
    'defaults_type' => 'no_custom',//カスタムボタンなし


    /* カスタムボタンの上限 */
    'max_custom_count' => 99,


    /* ガチャランク　一覧 */
    'ranks' => $ranks,



    /* 発送設定 */
    'shipped' => [

        # 発送ポイント(数値)
        'point' => (int)  0,

        # 商品数n個ごとに発送数を加算(数値)
        'item_count_unit'   => (int) 0,


        # 最低限発送に必要な、発送商品の合計ポイント上限(数値)
        'limit_prize_point' => (int) 1000,

    ],



    /* ガチャボタン設定 */
    'btn_settings' => [

        # 1回ガチャる
        'oneplay'     => true,

        # 10回ガチャる
        'tenplay'     => true,

        # 100連ボタン
        'hundredplay' => false,

        # カスタムボタン
        'custom'      => false,


        #　ポップアップボタン
        'popup'       => true,

    ],


    /* カテゴリー画像表示(スタートプラン：全てfalse) */
    'category_image' => [

        # PC用カテゴリー
        'pc'     => true,

        # モバイル用カテゴリー
        'mobile' => false,

        # フッターカテゴリー
        'footer' => true,

    ],



    /* 管理者ページ */
    'admin' => [

        # 管理者ページよりガチャ一覧設定の利用( フルパッケージよりtrue )
        'settings'   => false,

    ],




    /* ガチャボタン CSSクラス */
    'btn_styles' => [

        # 1回ガチャる
        'one_play' => [

            ## 販売中
            'active' => <<<__EOT__
            btn btn-sm btn-light bg-gradient fw-bold w-100 py-2 text-
            rounded-pill border-secondary border-1 shadow-sm
            position-relative shiny overflow-hidden
            __EOT__,

            ## 終了
            'soldout' => <<<__EOT__
            btn btn-sm btn-light bg-gradient fw-bold w-100 py-2 text-danger
            rounded-pill border-secondary border-1 shadow-sm
            __EOT__,

        ],

        # 10回ガチャる
        'ten_play' => [

            ## 販売中
            'active' => <<<__EOT__
            btn btn-sm btn-dark bg-gradient fw-bold w-100 py-2 text-white
            rounded-pill shadow-sm
            position-relative shiny overflow-hidden
            __EOT__,

            ## 終了
            'soldout' => <<<__EOT__
            btn btn-sm btn-dark bg-gradient fw-bold w-100 py-2 text-danger
            rounded-pill shadow-sm
            __EOT__,

        ],

        # 百回ガチャる
        'hundred_play' => [

            ## 販売中
            'active' => <<<__EOT__
            btn btn-sm btn-info bg-gradient fw-bold w-100 py-2 text-white
            rounded-pill shadow-sm
            position-relative shiny overflow-hidden
            __EOT__,

            ## 終了
            'soldout' => <<<__EOT__
            btn btn-sm btn-info bg-gradient fw-bold w-100 py-2 text-danger
            rounded-pill shadow-sm
            __EOT__,

        ],

        # カスタムボタン
        'coustom' => [

            ## 販売中
            'active' => <<<__EOT__
            btn btn-sm btn-info bg-gradient fw-bold w-100 py-2 text-white
            rounded-pill shadow-sm h-100
            position-relative shiny overflow-hidden
            __EOT__,

            ## 終了
            'soldout' => <<<__EOT__
            btn btn-sm btn-info bg-gradient fw-bold w-100 py-2 text-danger
            rounded-pill shadow-sm h-100
            disabled
            __EOT__,

        ],

    ],


];
