<?php
/*
|--------------------------------------------------------------------------
| 会員ランク・チケット　設定
|--------------------------------------------------------------------------
|
|
*/
return [


    # チケットシステムの利用
    'ticket'    => env('TIKET_SISTEM',    false),

    # 商品をチケットと交換
    'change_prize_to_ticket' => env('CHANG_PRIZE_TO_TIKET', false),




    # 会員ランクの利用
    'user_rank' => env('USER_RANK_SISTEM', false),

    # 会員ランクの設定
    'u_rank_settings' => [

        ## 毎月ボーナスの有無
        'monthly_bonuses'  => true,

        ## 即時ボーナス（ガチャの後）の有無
        'instant_bonuses'  => true,

        ## ランクアップ基準ID(ポイント履歴) 21:ガチャPLAY, 22:商品発送PT, 11:ポイント購入
        'point_history_id' => '21',


        ## 会員ランク　一覧
        'user_ranks' => [

            # ビギナー
            0 => [
                'label' => 'ビギナー',
                'code'  => 'beginner',
                'image' => 'site/image/user_rank/beginner.jpg',
                'rankup_ptcount'  => 0,
                'point_bonus'     => 0,
                'ticket_bonus'    => 0,
                'point_sail_ratio'=> 0,
            ],

            # ブロンズ
            100 => [
                'label' => 'ブロンズ',
                'code'  => 'bronze',
                'image' => 'site/image/user_rank/bronze.jpg',
                'rankup_ptcount'  => 10*1000,
                'point_bonus'     => 1*1000,
                'ticket_bonus'    => 10,
                'point_sail_ratio'=> 1,
            ],

            # シルバー
            200 => [
                'label' => 'シルバー',
                'code'  => 'silver',
                'image' => 'site/image/user_rank/silver.jpg',
                'rankup_ptcount'  => 50*1000,
                'point_bonus'     => 3*1000,
                'ticket_bonus'    => 50,
                'point_sail_ratio'=> 3,
            ],

            # ゴールド
            300 => [
                'label' => 'ゴールド',
                'code'  => 'gold',
                'image' => 'site/image/user_rank/gold.jpg',
                'rankup_ptcount'  => 200*1000,
                'point_bonus'     => 5*1000,
                'ticket_bonus'    => 200,
                'point_sail_ratio'=> 5,
            ],

            # ダイヤモンド
            400 => [
                'label' => 'ダイヤモンド',
                'code'  => 'diamond',
                'image' => 'site/image/user_rank/diamond.jpg',
                'rankup_ptcount'  => 500*1000,
                'point_bonus'     => 10*1000,
                'ticket_bonus'    => 500,
                'point_sail_ratio'=> 7,
            ],

            # マスター
            500 => [
                'label' => 'マスター',
                'code'  => 'master',
                'image' => 'site/image/user_rank/master.jpg',
                'rankup_ptcount'  => 1000*1000,
                'point_bonus'     => 30*1000,
                'ticket_bonus'    => 1*1000,
                'point_sail_ratio'=> 10,
            ],

            # レジェンド
            600 => [
                'label' => 'レジェンド',
                'code'  => 'legend',
                'image' => 'site/image/user_rank/legend.jpg',
                'rankup_ptcount'  => 3000*1000,
                'point_bonus'     => 50*1000,
                'ticket_bonus'    => 3*1000,
                'point_sail_ratio'=> 15,
            ],

        ],

        ## 会員ランク限定ガチャの利用
        // 'play_gacha' => 'only_rank', //会員ランクガチャのみ利用可能
        'play_gacha' => 'under_rank',//会員ランク以下のガチャ全て利用可能

    ],








];
