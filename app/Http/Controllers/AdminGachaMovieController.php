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
|  サイト管理者 ガチャの演出動画　コントローラー
| =============================================
*/
class AdminGachaMovieController extends Controller
{
    /**
     * 編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        return view('admin.gacha.movie.edit', compact('gacha'));
    }
}
