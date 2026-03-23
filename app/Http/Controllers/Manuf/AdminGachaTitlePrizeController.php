<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitlePrize;
use App\Models\Prize;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル 商品 コントローラー
| =============================================
*/
class AdminGachaTitlePrizeController extends Controller
{
    /**
     * 一覧
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function index( ManufGachaTitle $gacha_title )
    {
        # タイトル商品 一覧
        $title_prize = $gacha_title->title_prizes;


        return view('manuf_admin.gacha_title.title_prize.index', compact(
            'gacha_title','title_prize',
        ));
    }



    /**
     * 詳細
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function show( ManufGachaTitle $gacha_title )
    {
        return view('manuf_admin.gacha_title.show', compact('gacha_title'));
    }


}
