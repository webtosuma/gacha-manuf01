<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GachaPlayCreateUserPrizeMethod
as CreateUserPrize;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\PointHistory;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use App\Models\Prize;
use App\Models\GachaRankMovie;
use App\Models\Movie;
/*
| =============================================
|  ガチャ 広告ガチャPLAY コントローラー
| =============================================
*/
class GachaSponsorAdController extends Controller
{
    /**
     * PLAYガチャで遊ぶ
     * @param \Illuminate\Http\Request $request
     * @param String $category_code      //カテゴリーコード名
     * @param  \App\Models\Gacha  $gacha //ガチャモデル
     * @param String $key                //ガチャモデル・キー
     * @return \Illuminate\Http\Response
     */
    public function movie(Request $request, $category_code, Gacha $gacha, $key)
    {
        dd('movie');
    }
}
