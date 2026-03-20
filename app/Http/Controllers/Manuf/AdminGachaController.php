<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Requests\AdminGachaRequest;
use App\Models\GachaCategory;
// use App\Models\Gacha;
// use App\Models\GachaDiscription;
// use App\Models\GachaPrize;
// use App\Models\Prize;
// use App\Models\UserGachaHistory;
// use App\Models\UserRankHistory;
// use App\Models\PointSail;
/*
| =============================================
|  Manufacturer/Admin : ガチャ(タイトル) コントローラー
| =============================================
*/
class AdminGachaController extends Controller
{
    /**
     * 一覧
     *
     * @param Request $request　
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, $category_code=null )
    {
        # カテゴリーコードの確認
        $gacha_category = GachaCategory::where('code_name',$category_code)->first();
        if(!$gacha_category&&$category_code){ return \App::abort(404); }//該当なし

        return view('manuf_admin.gacha.index', compact('gacha_category','category_code'));
    }
}
