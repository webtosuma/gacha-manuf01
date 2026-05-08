<?php

namespace App\Services\Gacha;
use App\Models\GachaPrize;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use BcMath\Number;
use Ramsey\Uuid\Type\Integer;

/*
| =============================================
|  ガチャ PLAY：抽選ロジック [当選商品の決定]　 サービス
| =============================================
*/
class PlayDrawWinService
{
    /** サービスの登録 */
    public function __construct(
        # 抽選ロジック
        // protected PlayDrawService    $drawService,
        # 特殊なガチャランク
        protected SpecialRankService $specialRankService,
    ){}



    /**
     * 当選商品の決定
     * (通常当選・特殊当選　共通)
     * @param UserGachaHistory $history
     * @param String $type
     * @param Array $g_prize_nomal
    */
    public function index(
        $history, $type, $g_prize_nomal
    ): array
    {
        # 特殊なガチャランク
        $ranks   = $this->specialRankService->getList();

        # 特殊なガチャランクID
        $rank_id = $type ? $ranks[$type] : null;

        # 特殊なガチャランクの当選商品ID
        $g_prize_sp_id = null;
        switch( $type )
        {
            # 通常の当選
            case null: /*処理なし*/ break;

            # ラストワン
            case 'lastone':
                $g_prize_sp_id = $this->winLastone($history, $rank_id);
                break;

            # その他の特殊なガチャランクの当選
            default:
                $g_prize_sp_id = $this->winSp($history, $rank_id);
                break;
            /**/
        }


        # 通常の当選商品(ラストワンは除く)
        if( !in_array($type,[ 'lastone']) ){
            list( $new_array, $g_prize_id ) = $this->winNomal($history, $g_prize_nomal);

            ## 特殊な当選商品IDがあれば上書き
            $g_prize_id = $g_prize_sp_id ?? $g_prize_id;
        }
        # 通常の当選商品(ラストワン)
        else{
            list( $new_array, $g_prize_id ) = [$g_prize_nomal, $g_prize_sp_id];
        }


        return [ $new_array, $g_prize_id ];
    }



    /**
     * ユーザー商品の登録・ガチャ商品の残数更新
     * (通常当選・特殊当選　共通)
     * @param UserGachaHistory $history
     * @param GachaPrize $g_prize
     */
    public function createUserPrize(
        UserGachaHistory $history, GachaPrize $g_prize
    ): UserPrize
    {
        # ユーザー取得商品の登録
        return UserPrize::create([
            'gacha_history_id' => $history->id,
            'user_id'  => $history->user_id,
            'prize_id' => $g_prize->prize_id,
            'point'    => $g_prize->prize->point,
        ]);
    }



    /**
     * 通常の当選
     * @param UserGachaHistory $history
    */
    public function winNomal( $history, $array ): array
    {
        # 残りの合計工数
        $total = array_sum(array_column( $array, 'remaining_count' ) );

        # ランダムな当選
        $rand = mt_rand(1, $total);

        # 当選ガチャ商品
        $current = 0;
        foreach ($array as &$item) //&参照（reference）:配列の中を直接操作
        { 
            $current += $item['remaining_count'];

            if ($rand <= $current) {

                $item['remaining_count']--;//配列側だけ減算
                $g_prize_id = $item['id'];

                return [ $array, $g_prize_id ];
            }
        } 
         
        return [];
    }



    /**
     * ラストワンの当選
     * @param UserGachaHistory $history
     * @param Integer $rank_id
    */
    public function winLastone( $history, $rank_id ): int
    {
        # ガチャ商品情報(ラストワン)
        $g_prizes = $history->gacha->g_prizes;

        # 特殊なガチャランクの当選商品
        $g_prize_sp = $g_prizes->firstWhere('gacha_rank_id', $rank_id);

        # ガチャ商品(ラストワン)の残数減算
        $g_prize_sp->decrement('remaining_count');


        return $g_prize_sp->id;
    }



    /**
     * 特殊なガチャランクの当選
     * @param UserGachaHistory $history
     * @param Integer $rank_id
    */
    public function winSp( $history, $rank_id ): int
    {
        # ガチャ商品情報
        $g_prizes = $history->gacha->g_prizes;

        # 特殊なガチャランクの当選商品
        $g_prize_sps = $g_prizes->where('gacha_rank_id', $rank_id);


        # ランダムな当選 
        $g_prize_sp = $g_prize_sps->random();


        return $g_prize_sp->id;
    }



}