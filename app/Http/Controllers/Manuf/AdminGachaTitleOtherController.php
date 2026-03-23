<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManufGachaTitle;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル その他の処理 コントローラー
| =============================================
*/
class AdminGachaTitleOtherController extends Controller
{
    /**
     * 演出動画情報の編集
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function movie_edit( ManufGachaTitle $gacha_title )
    {
        return view('manuf_admin.gacha_title.movie.edit', compact(
            'gacha_title'
        ) );
    }



    /**
     * 公開設定の編集
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function published_edit( ManufGachaTitle $gacha_title )
    {
        return view('manuf_admin.gacha_title.published.edit', compact(
            'gacha_title'
        ) );
    }



    /**
     * 履歴
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function history( ManufGachaTitle $gacha_title )
    {
        return view('manuf_admin.gacha_title.history.index', compact(
            'gacha_title'
        ) );
    }



}
