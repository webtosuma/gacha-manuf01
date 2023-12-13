<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
use App\Models\Prize;
/*
| =============================================
|  ガチャの商品 サイト管理者 コントローラー
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
        dd( $request->all() );

        // DB::beginTransaction();
        // try {

            # ランク別詳細情報
            self::updateFunc( $request, $gacha);

        //     DB::commit();
        // } catch (\Exception $e) {

        //     Log::error($e);
        //     DB::rollback();
        //     $message = 'エラーが発生しました。';
        //     return redirect()->back()
        //     ->with(['alert-danger'=>$message,'icon'=>'bi-exclamation-circle']);

        // }
        // // 二重送信防止
        // $request->session()->regenerateToken();


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

            $gacha_rank_id = $discription->gacha_rank_id;
            $key = 'gri'.$gacha_rank_id; //識別キー gri100
            // dd($key);

            $new_prize_ids    = $request[$key.'-new_prize_ids'];      //新規商品：商品ID
            $new_prize_counts = $request[$key.'-new_prize_counts'];   //新規商品：商品数
            $gacha_prizes     = $discription->g_prizes; //登録ずみ商品
            $gacha_prize_counts = $request[$key.'-gacha_prize_counts']; //登録ずみ商品：商品数


            ## 新規商品の登録
            if ($new_prize_ids)
            {
                foreach ($new_prize_ids as $num => $prize_id)
                {
                    $count = $new_prize_counts[$num];//商品数

                    if( $count==0 ){ continue; }//処理スキップ

                    $gacha_prize = new GachaPrize([
                        'gacha_id'       => $gacha->id, //ガチャリレーション
                        'prize_id'       => $prize_id,  //商品リレーション
                        'gacha_rank_id'  => $gacha_rank_id, //ランクID
                        'max_count'      => $count, //景品総数
                        'remaining_count'=> $count, //景品残数
                    ]);
                    $gacha_prize->save();
                }
            }


            ## 登録ずみ商品の商品数更新・削除
            if ($gacha_prizes)
            {
                foreach ($gacha_prizes as $num => $gacha_prize)
                {
                    $count = $gacha_prize_counts[$num];//更新商品数
                    $count = self::SpecialRankCount( $count,$gacha_rank_id, $gacha );//特殊なカウントの自動計算

                    /* 更新 */
                    if( $count > 0 ){
                        if( $count == $gacha_prize->max_count ){ continue; }//処理スキップ

                        $gacha_prize->update([
                            'max_count'       => $count, //景品総数
                            'remaining_count' => $count, //景品残数
                        ]);
                    }
                    /* 削除 */
                    else{
                        $gacha_prize->delete();
                    }
                }
            }
        }
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
            case 10:
                $count = 1;
                // dd('lastone'.$count);
                break;
            //
            # キリ番
            case 310:
                $array = GachaPlayCreateUserPrizeMethod::KiriHitsCalc( $gacha );
                $count = count( $array );
                // dd('kiri'.$count);
                break;
            //
            # ゾロ目
            case 320:
                $array = GachaPlayCreateUserPrizeMethod::ZoroHitsCalc( $gacha );
                $count = count( $array );
                // dd('zoro'.$count);
                break;
            //
            default:
                $count = $count;//更新なし
                break;
            //
        }
        return $count;
    }

}
