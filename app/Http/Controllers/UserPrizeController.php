<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
use App\Models\PointHistory;
/*
| =============================================
|  取得した景品一覧 コントローラー
| =============================================
*/
class UserPrizeController extends Controller
{
    /**
     * 表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user_prize.index');
    }


    /**
     * 景品のポイント交換
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exchange_points(Request $request)
    {
        # 景品のポイント交換
        self::ExchangePoints($request);

        # メッセージ
        $message = '指定した景品をポイント交換しました。';

        return view('user_prize.index',compact('message'))
        ->with('alert-warning','ログインしました。');
    }



    /**
     * 景品のポイント交換
     * @return Void
     */
    public static function ExchangePoints($request)
    {
        # 変数の定義
        $user =Auth::user();
        $id_array = $request->user_prize_ids;

        # ポイント交換するユーザー景品を取得
        $user_prizes = UserPrize::where('user_id',$user->id)
        ->find( $id_array );

        # 交換ポイントの合計($total_point)
        $total_point = 0;
        foreach ($user_prizes as $user_prize) {
            $total_point += $user_prize->prize->point;
        }

        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,    //ユーザー　リレーション
            'value'     => $total_point, //ポイント数
            'reason_id' => 12 // '景品のポイント交換',
        ]);
        $point_history->save();


        # ユーザー取得景品情報の更新
        foreach ($user_prizes as $user_prize) {
            $user_prize->point_history_id = $point_history->id;
            $user_prize->save();
        }

        // 二重送信防止
        $request->session()->regenerateToken();
    }

}

