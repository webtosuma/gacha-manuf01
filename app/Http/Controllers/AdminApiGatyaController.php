<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Gacha;

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

}
