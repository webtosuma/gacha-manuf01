<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Http\Controllers\GachaPlayCreateUserPrizeMethod;
/*
| =============================================
|  Admin : ガチャ商品 サービス
| =============================================
*/
class GachaPrizeService
{
    /**
     * 更新
     *
     * @param Request $request
     * @param Gacha $gacha
     * @return Gacha
     */
    public function update($request, $gacha): Gacha
    {
        return DB::transaction(function () use ($request, $gacha) {


            $this->updatePrizes($request, $gacha);
            return $gacha;

            
        });
    }



    /**
     * 更新処理
     * 
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     * @return Void
    */
    private function updatePrizes($request, $gacha)
    {
        $discriptions = $gacha->discriptions;

        foreach ($discriptions as $discription) {

            $gacha_rank_id = $discription->gacha_rank_id;
            $key = 'gri' . $gacha_rank_id;

            $new_prize_ids      = $request[$key . '-new_prize_ids']          ?? []; //新規商品：商品ID
            $new_prize_counts   = $request[$key . '-new_prize_counts']       ?? []; //新規商品：商品数
            $new_special_counts = $request[$key . '-new_special_counts']     ?? []; //新規特別な商品：商品数

            $gacha_prizes       = $discription->g_prizes                     ?? []; //登録ずみガチャ商品
            $gacha_prize_counts = $request[$key . '-gacha_prize_counts']     ?? []; //登録ずみガチャ商品：商品数
            $special_counts     = $request[$key . '-special_counts']         ?? []; //登録ずみ特別な商品：商品数

            $delete_ids         = $request[$key . '-delete_gacha_prize_ids'] ?? []; //削除ガチャ商品ID


            // 口数が0でも可な特別なランク
            $is_special_rank = in_array($gacha_rank_id, [
                GachaPlayCreateUserPrizeMethod::GachaRankIdSspita(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdSpita(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdApita(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdSecretKiri(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdSecretPita(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdKiri(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdZoro(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdPita(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdUserKiri(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdUserZoro(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdUserPita(),
                GachaPlayCreateUserPrizeMethod::GachaRankIdSlide(),
            ]);

            ## 新規商品の登録
            foreach ($new_prize_ids as $i => $prize_id) 
            {
                $count = $new_prize_counts[$i] ?? 0;
                $count = $this->specialRankCount($count, $gacha_rank_id);

                $special_count = $new_special_counts[$i] ?? null;

                if (!$is_special_rank && $count == 0) continue;

                GachaPrize::create([
                    'gacha_id' => $gacha->id,
                    'prize_id' => $prize_id,
                    'gacha_rank_id' => $gacha_rank_id,
                    'max_count' => $count,
                    'remaining_count' => $count,
                    'special_count' => $special_count,
                ]);
            }


            ## 登録ずみ商品の商品数更新・削除
            foreach ($gacha_prizes as $i => $gacha_prize) 
            {
                $count = $gacha_prize_counts[$i] ?? 0;
                $count = $this->specialRankCount($count, $gacha_rank_id);

                $special_count = $special_counts[$i] ?? null;

                /* 更新 */
                if (!$is_special_rank && $count == 0) {
                    $gacha_prize->delete();
                } 
                /* 削除 */
                else {
                    $gacha_prize->update([
                        'max_count' => $count,
                        'remaining_count' => $count,
                        'special_count' => $special_count,
                    ]);
                }
            }

            ## 登録ずみ商品の削除
            foreach ($delete_ids as $id) {
                GachaPrize::find($id)?->delete();
            }
        }

        # 売り切れの解除
        if ($gacha->max_count) {
            $gacha->update(['is_sold_out' => 0]);
        }

        # 売り切れ登録(残数が口数以下のとき)
        if ($gacha->remaining_count < 1) {
            $gacha->update([
                'sold_out_at' => now(),
                'is_sold_out' => 1,
            ]);
        }

        # 商品登録更新日の更新
        $gacha->update(['updated_prizes_at' => now()]);
    }





    /**
     * 特殊なランクのcount計算
    */
    private function specialRankCount($count, $gacha_rank_id)
    {
        if ($count == 0) return 0;

        switch ($gacha_rank_id) 
        {
            # ラストワン
            case 10: return 1;

            case 173: # RankSS ピタリ
            case 273: # RankS ピタリ
            case 373: # RankA ピタリ

            case 310: # キリ番
            case 320: # ゾロ目
            case 330: # ピタリ賞
            case 361: # 個人キリ番
            case 362: # 個人ゾロ目
            case 363: # 個人ピタリ賞
            case 901: #シークレット・キリ
            case 903: #シークレット・ピタリ
                return 0;

            # その他　更新なし
            default:
                return $count;
        }
    }



}