<?php

namespace App\Services\Gacha;
use Illuminate\Http\Request;
use App\Models\Gacha;
/*
| =============================================
|  ガチャ PLAY バリデーション サービス
| =============================================
*/
class PlayValidationService
{
    /**
     * バリデーション
     * @param Request $request
     * @param Gacha $gacha
     * @param Bool  $admin
    */
    public function index($request,$gacha,$admin=false):? string
    {
        # 変数
        $user = $request->user();
        $now_play_count   = (int) $request->play_count;    //プレイ回数
        $play_point       = (int) $gacha->one_play_point;  //ガチャの1回プレー使用ポイント
        $total_play_point = $now_play_count * $play_point; //合計使用ポイント
        $remaining_count  = (int) $gacha->remaining_count; //残りのプレイできる回数
        $is_sold_out      = (bool) $gacha->remaining_count < 1; //売り切れかどうか
    
        # ログインしていないとき
        if( !$user ){
            return 'ガチャを始めるには、ログインが必要です';
        }
        # 売り切れ
        else if( $is_sold_out ){
            return '売り切れです';
        }

        # ポイントが不足しているとき
        else if( $total_play_point > $user->point ){
            return 'ポイントが不足しています';
        }

        # 商品数が、プレイ回数より少ないとき
        else if( $now_play_count > $remaining_count ){
            return '残りの商品数が少ないため、複数回ガチャをすることができません';
        }

        # 非公開ガチャの利用不可(非公開または、公開日が現在より先のとき)
        else if( !$gacha->is_published && !$admin ){
            return '現在、このガチャを利用することはできません。';
        }
        # [会員ランク限定]
        else if(
            $gacha->dont_auth_user_rank
        ){
            return 'この会員ランクガチャを利用することはできません。';
        }
        # [限定ガチャ]1回or10回限定
        else if(
            $gacha->type=='one_chance' &&  (
                $gacha->played_one_time || ! ($now_play_count==1 || $now_play_count==10)
            )
        ){
            return '現在、このガチャを利用することはできません。';
        }
        # [限定ガチャ]１回限定ガチャ
        else if(
            $gacha->type=='one_time' && (
                $gacha->played_one_time || $now_play_count >1
            )
        ){
            return '現在、このガチャを利用することはできません。';
        }
        # [限定ガチャ]一日一回限定限定ガチャ
        else if(
            $gacha->type=='only_oneday' && (
                $gacha->played_only_oneday || $now_play_count >1
            )
        ){
            return '本日既に、このガチャは利用済みです。';
        }
        # [限定ガチャ] n回限定
        else if(
            in_array( $gacha->type,[ 'n_time', 'n_time_no_custom', ] )
            && ($gacha->type_n_remaining_count < $now_play_count)
        ){
            return '指定の回数は、上限回数をオーバーしています。';
        }
        # [限定ガチャ] 1日n回限定
        else if(
            in_array( $gacha->type,[ 'n_oneday', 'n_oneday_no_custom', ] )
            && ($gacha->type_n_remaining_count < $now_play_count)
        ){
            return '指定の回数は、一日の上限回数をオーバーしています。';
        }


        # [限定ガチャ]新規登録ユーザー定限定ガチャ
        else if(
            $gacha->type=='only_new_user' && (
                $user->sevendays_affter_registar || $gacha->played_one_time || $now_play_count >1
            )
        ){
            return 'このガチャを利用することはできません。';
        }
        # [時間限定ガチャ]
        else if(!$gacha->is_show_timezone) /*-- (時間帯限定)表示可能か否か --*/
        {
            return '只今このガチャは公開時間外です。';
        }
        # [スポンサー広告ガチャ]1日の上限を超える
        else if($gacha->played_ad_limit)
        {
            return '一日の利用上限を超えました。';
        }
    
        return null;
    }

}