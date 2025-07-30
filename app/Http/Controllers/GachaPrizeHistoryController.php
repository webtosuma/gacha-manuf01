<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
// use App\Models\PointHistory;
// use App\Models\Infomation;
// use App\Models\Movie;
/*
| =============================================
|  ガチャ商品排出履歴 コントローラー
| =============================================
*/
class GachaPrizeHistoryController extends Controller
{
    /**
     * 履歴
     * @param String $category_code      //カテゴリーコード名
     * @param Gacha  $gacha
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function index( $category_code, Gacha $gacha, $key)
    {
        # キーのチェック
        if( $gacha->key!=$key || !$gacha->published_at ){ return \App::abort(404); }

        # 商品履歴の表示許可
        // if( ! config('app.gacha_prize_history') ){ return \App::abort(404); }

        # 売切れチェック
        if( $gacha->remaining_count!=0 ){ return \App::abort(404); }


        ## 表示できるガチャ一覧
        $category_code = $gacha->category->code_name;
        $gachas = GachaController::getPublishedGachas( $category_code, null );


        # 排出結果
        $gacha_id = $gacha->id;
        $user_prizes = UserPrize::whereHas('ug_history', function ($query) use ($gacha_id) {
            $query->where('gacha_id', $gacha_id);
        })->orderBy('created_at', 'asc')
        ->with('user')->paginate(20);


        return view('gacha.prize_history', compact(
            'gacha',
            'gachas','category_code',
            'user_prizes',
        ));
    }




    /**
     * API一覧
     *
     * @param \Illuminate\Http\Request $request
     * @param Gacha $gacha
     * @return \Illuminate\Http\Response
     */
    public function api( Request $request, Gacha $gacha )
    {
        # 排出結果
        $gacha_id = $gacha->id;
        $user_prizes = UserPrize::whereHas('ug_history', function ($query) use ($gacha_id) {
            $query->where('gacha_id', $gacha_id);
        })->orderBy('created_at', 'asc')
        ->with('user','prize')->paginate(20);

        return response()->json( compact('user_prizes') );
    }


}
