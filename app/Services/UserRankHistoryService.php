<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\UserRankHistoryController;
use App\Models\User;
/*
| =============================================
|  会員ランク履歴 サービス
| =============================================
*/
class UserRankHistoryService
{
    /**
     * ガチャ実行後の昇格の評価
     * @param User $user
     */
    public function EvaluationPlaied($user): bool
    {
        return DB::transaction(function () use ($user) {
        
            $rank_up = false;

            if(
                $user->now_rank
    
                //[会員ランクの利用]config.u_rank_ticketにて設定
                && (bool) config('u_rank_ticket.user_rank',false)
    
                //[即時ボーナスの有無]config.u_rank_ticketにて設定
                && (bool) config('u_rank_ticket.u_rank_settings.instant_bonuses', true )
    
            ){
                $rank_up = UserRankHistoryController::CreateRankUpHistory( $user, now(), $user->now_rank );
    
                ## ランクアップ時のボーナス付与
                if( $rank_up ){
                    $desc_first_rank = $user->desc_first_rank;//更新された直近の会員ランク履歴
                    UserRankHistoryController::CreateBonusHistory( $user, now(), $desc_first_rank );
                }
    
            }
    
            return $rank_up;
        });
    }



}