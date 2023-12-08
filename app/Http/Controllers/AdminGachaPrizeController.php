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
     * ガチャの商品　編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        return view('admin.gacha.prize.edit', compact('gacha'));
    }
}
