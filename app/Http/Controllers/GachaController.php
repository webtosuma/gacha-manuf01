<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gacha;
use App\Models\PointHistory;

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
        return redirect()->route('home');
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
        # 変数
        $user = Auth::user(); //ログインユーザー取得
        $play_point = 100;    //ガチャの1回プレー使用ポイント　＊<------あとで修正
        $play_count = $request->play_count; //プレイ数

        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,          //ユーザー　リレーション
            'value'     => - ( $play_point * $play_count ), //使用ポイント数
            'reason_id' => 21 //入出理由ID
        ]);
        $point_history->save();

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
