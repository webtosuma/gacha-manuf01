<?php

namespace App\Http\Controllers;
use App\Http\Controllers\GachaPlayCreateUserPrizeMethod;//

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
use App\Models\Prize;
/*
| =============================================
|  サイト管理者　ガチャの商品 コントローラー
| =============================================
*/
class AdminGachaPrizeController extends Controller
{
    /**
     * 編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        return view('admin.gacha.prize.edit', compact('gacha'));
    }




    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gacha $gacha)
    {

        # ランク別詳細情報
        DB::beginTransaction();
        try {

            # ランク別詳細情報
            self::updateFunc( $request, $gacha);

            # 操作ログの更新
            AdminLogController::createLog( 'gacha.prize', $gacha->id );

            DB::commit();


        } catch (\Exception $e) {

            // Log::error($e);
            DB::rollback();            $message = 'エラーが発生しました。';
            return redirect()->back()
            ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);

        }
        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.gacha.prize.edit',$gacha)
        ->with(['alert-warning'=>'ガチャの登録商品を更新しました']);
    }



    /**
     * 更新処理
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     * @return Void
    */
    public function updateFunc( $request, $gacha)
    {
        $discriptions = $gacha->discriptions;
        foreach ($discriptions as $discription) {

            /* 変数の定義 */

                $gacha_rank_id = $discription->gacha_rank_id;
                $key = 'gri'.$gacha_rank_id; //識別キー gri100

                $new_prize_ids           = $request[$key.'-new_prize_ids'];           //新規商品：商品ID
                $new_prize_counts        = $request[$key.'-new_prize_counts'];        //新規商品：商品数
                $new_special_counts      = $request[$key.'-new_special_counts'];      //新規特別な商品：商品数

                $gacha_prizes            = $discription->g_prizes;                    //登録ずみガチャ商品
                $gacha_prize_counts      = $request[$key.'-gacha_prize_counts'];      //登録ずみガチャ商品：商品数
                $special_counts          = $request[$key.'-special_counts'];          //登録ずみ特別な商品：商品数

                $delete_gacha_prize_ids  = $request[$key.'-delete_gacha_prize_ids'];  //削除ガチャ商品ID

                // 口数が0でも可な特別なランク
                $is_special_rank = in_array( $gacha_rank_id, [
                    GachaPlayCreateUserPrizeMethod::GachaRankIdSspita(),//RankSS ピタリ
                    GachaPlayCreateUserPrizeMethod::GachaRankIdSpita(), //RankS  ピタリ
                    GachaPlayCreateUserPrizeMethod::GachaRankIdApita(), //RankA  ピタリ

                    GachaPlayCreateUserPrizeMethod::GachaRankIdSecretKiri(),//シークレット・キリID
                    GachaPlayCreateUserPrizeMethod::GachaRankIdSecretPita(),//シークレット・ピタリID
                    GachaPlayCreateUserPrizeMethod::GachaRankIdKiri(),//キリ番ID
                    GachaPlayCreateUserPrizeMethod::GachaRankIdZoro(),//ゾロ目ID
                    GachaPlayCreateUserPrizeMethod::GachaRankIdPita(),//ピタリ賞ID
                    GachaPlayCreateUserPrizeMethod::GachaRankIdUserKiri(),//個人キリ番ID
                    GachaPlayCreateUserPrizeMethod::GachaRankIdUserZoro(),//個人ゾロ目ID
                    GachaPlayCreateUserPrizeMethod::GachaRankIdUserPita(),//個人ピタリ賞ID
                    GachaPlayCreateUserPrizeMethod::GachaRankIdSlide(),//スライド表示ID
                ] );


            /* ~ */


            ## 新規商品の登録
            if ($new_prize_ids)
            {
                foreach ($new_prize_ids as $num => $prize_id)
                {
                    $count = $new_prize_counts[$num] ?? 0;//商品数
                    $count = self::SpecialRankCount( $count,$gacha_rank_id, $gacha );//特殊なカウントの自動計算
                    $special_count = $new_special_counts[$num] ?? null;//特別な商品数


                    if( !$is_special_rank && $count==0 ){ continue; }//処理スキップ

                    $gacha_prize = new GachaPrize([
                        'gacha_id'       => $gacha->id,      //ガチャリレーション
                        'prize_id'       => $prize_id,       //商品リレーション
                        'gacha_rank_id'  => $gacha_rank_id,  //ランクID
                        'max_count'      => $count,          //景品総数
                        'remaining_count'=> $count,          //景品残数
                        'special_count'  => $special_count,  //特別な商品数
                    ]);
                    $gacha_prize->save();
                }
            }


            ## 登録ずみ商品の商品数更新・削除
            if ($gacha_prizes)
            {
                foreach ($gacha_prizes as $num => $gacha_prize)
                {
                    $count = $gacha_prize_counts[$num] ?? 0;//商品数
                    $count = self::SpecialRankCount( $count,$gacha_rank_id, $gacha );//特殊なカウントの自動計算
                    $special_count = $special_counts[$num] ?? null;//特別な商品数


                    /* 更新 */
                    if( ! (!$is_special_rank && $count==0) ){

                        $gacha_prize->update([
                            'max_count'       => $count, //景品総数
                            'remaining_count' => $count, //景品残数
                            'special_count'   => $special_count, //特別な商品数
                        ]);

                    }
                    /* 削除 */
                    else{ $gacha_prize->delete(); }
                }
            }


            ## 登録ずみ商品の削除
            if ($delete_gacha_prize_ids)
            {
                foreach ($delete_gacha_prize_ids as $id) {
                    $gacha_prize = GachaPrize::find($id);
                    $gacha_prize->delete();
                }
            }
        }

        # 売り切れの解除
        if($gacha->max_count){ $gacha->update(['is_sold_out'=>0]); }

        # 売り切れ登録(残数が口数以下のとき)
        if( $gacha->remaining_count < 1 ){
            $gacha->update([
                'sold_out_at' => now(),
                'is_sold_out' => 1,
            ]);
        }

        # 商品登録更新日の更新
        $gacha->update(['updated_prizes_at'=>now()]);

    }


    /**
     * 特殊なランクのcount計算
    */
    public function SpecialRankCount( $count,$gacha_rank_id, $gacha )
    {
        if($count == 0){ return 0; }

        switch ($gacha_rank_id)
        {
            # ラストワン
            case 10:  $count = 1; break;
            //
            # RankSS ピタリ
            case 173: $count = 0; break;
            # RankS ピタリ
            case 273: $count = 0; break;
            # RankA ピタリ
            case 373: $count = 0; break;

            //
            # キリ番
            case 310: $count = 0; break;
            # ゾロ目
            case 320: $count = 0; break;
            # ピタリ賞
            case 330: $count = 0; break;
            //
            # 個人キリ番
            case 361: $count = 0; break;
            # 個人ゾロ目
            case 362: $count = 0; break;
            # 個人ピタリ賞
            case 363: $count = 0; break;
            //
            #シークレット・キリ
            case 901: $count = 0; break;
            #シークレット・ピタリ
            case 903: $count = 0; break;
            //
            # その他　更新なし
            default: $count = $count; break;
            //
        }
        return $count;
    }

}
