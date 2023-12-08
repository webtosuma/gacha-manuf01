<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;

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
        $category = GachaCategory::all();

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
        $gacha = Gacha::find($gacha->id);
        $discriptions = $gacha->discriptions;

        foreach ($discriptions as $discription) {
            $discription->rank_label = $discription->rank_label; //ランクラベル
            $g_prizes   = $discription->g_prizes;   //ガチャ商品
        }

        return response()->json( $discriptions );
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
