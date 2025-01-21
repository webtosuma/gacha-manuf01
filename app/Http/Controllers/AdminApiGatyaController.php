<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
/*
| =============================================
|  ガチャ サイト管理者API コントローラー
| =============================================
*/
class AdminApiGatyaController extends Controller
{

    /**
     * カテゴリ一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request)
    {
        $category = GachaCategory::orderBy('created_at')->get();

        return response()->json( $category );
    }



    /**
     * ランク情報の取得
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gacha $gacha
     * @return \Illuminate\Http\Response
     */
    public function ranks(Request $request, Gacha $gacha)
    {
        # ガチャ情報
        $gacha = Gacha::find($gacha->id);
        $gacha->max_count = $gacha->max_count;
        $gacha->total_play_point = $gacha->one_play_point * $gacha->max_count;//合計ポイント
        $gacha->total_point = $gacha->total_point;

        # ランク情報
        $discriptions = $gacha->discriptions;
        foreach ($discriptions as $discription) {
            $discription->rank_label = $discription->rank_label; //ランクラベル

            $discription->total_count_format     = $discription->total_count_format;     //口数(g_prizes_max_count)
            $discription->average_point_format   = $discription->average_point_format;
            $discription->winning_ratio_format   = $discription->winning_ratio_format;

            $discription->hit_nums = $discription->hit_nums;//商品の合当選ガチャPLAY数

            $discription->gacha_prizes_count = GachaPrize::where('gacha_id',$gacha->id)->where('gacha_rank_id',$discription->gacha_rank_id)
            ->get()->count();//登録が茶用品の種類数

            $ratio = $gacha->max_count
            ? $discription->g_prizes->sum('max_count')/$gacha->max_count*100 :0;
            $discription->g_prizes_ratio = round( $ratio, 2);//当選率(g_prizes_ratio)

        }

        return response()->json(compact('gacha','discriptions'));
    }




    /**
     * ガチャランク別、ガチャ商品情報の取得
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\GachaDiscription $discription
     * @return \Illuminate\Http\Response
     */
    public function ranks_gacha_prizes(Request $request, GachaDiscription $discription)
    {
        $g_prizes = $discription->g_prizes;
        foreach ($g_prizes as $g_prize) {
            $g_prize->prize = $g_prize->prize; //商品情報
            $g_prize->prize->rank = $g_prize->prize->rank; //商品情報
            $g_prize->prize->image_path = $g_prize->prize->image_path;//商品画像
        }


        return response()->json( $g_prizes );
    }
}
