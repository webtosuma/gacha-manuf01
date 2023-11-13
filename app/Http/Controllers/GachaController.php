<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gacha;
/*
| =============================================
|  ガチャ コントローラー
| =============================================
*/
class GachaController extends Controller
{
    /**
     * カテゴリー選択・一覧表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * 詳細表示
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function show($gacha)
    {
        return view('gacha.show');
    }

    /**
     * PLAYガチャで遊ぶ
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     *
     * @return \Illuminate\Http\Response
     */
    public function play(Request $request)
    {
        return view('gacha.play');
    }

    /**
     * PLAYガチャのガチャカの結果表示
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     *
     * @return \Illuminate\Http\Response
     */
    public function result(Request $request)
    {
        return view('gacha.result');
    }
}
