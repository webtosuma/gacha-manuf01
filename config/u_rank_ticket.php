<?php
/*
|--------------------------------------------------------------------------
| ユーザーランク・チケット　設定
|--------------------------------------------------------------------------
|
|
*/
return [

    # ユーザーランクの利用
    'user_rank' => env('USER_RANK_SISTEM', false),

    # ユーザーランクの設定
    'u_rank_settings' => [

        ## 毎月ボーナスの有無
        'monthly_bonuses'  => true,

        ## 即時ボーナス（ガチャの後）の有無
        'instant bonuses'  => true,

        ## 即時ボーナス（ガチャの後）の有無
        'urnk_up_criteria' => '',

    ],



    # チケットシステムの利用
    'ticket'    => env('TIKET_SISTEM',    false),

    # 商品をチケットと交換
    'change_prize_to_ticket' => env('CHANG_PRIZE_TO_TIKET', false),




];
