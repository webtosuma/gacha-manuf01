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

    # チケットシステムの利用
    'ticket'    => env('TIKET_SISTEM',    false),

    # 商品をチケットと交換
    'change_prize_to_ticket' => env('CHANG_PRIZE_TO_TIKET', false),



];
