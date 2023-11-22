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
|  ガチャの詳細説明 サイト管理者 コントローラー
| =============================================
*/
class AdminGachaDisriptionController extends Controller
{
    /**
     * 詳細説明　編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        return view('admin.gacha.discription.edit', compact('gacha'));
    }


}
