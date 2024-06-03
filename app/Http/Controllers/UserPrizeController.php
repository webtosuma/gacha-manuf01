<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
use App\Models\Prize;
use App\Models\PointHistory;
use App\Models\GachaCategory;

/*
| =============================================
|  取得した商品一覧 コントローラー
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
     * 商品のポイント交換
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exchange_points(Request $request)
    {
        # 商品のポイント交換
        $data = self::ExchangePoints($request);
        $point_history = $data['point_history'];
        $user_prizes   = $data['user_prizes'];

        # メッセージ
        if( $user_prizes->count()>0 ){

            $point = number_format( $point_history->value );
            $message = '合計'.$user_prizes->count()."点の商品を\n".$point."ptに交換しました。";

            return redirect('user_prize')
            ->with('alert-warning',$message);
        }
        else{
            $message = '不正な処理を検知しました。';

            return redirect('user_prize')
            ->with(['alert-danger'=>$message, 'icon'=>'bi-exclamation-circle' ]);
        }
    }



    /**
     * 商品のポイント交換
     * @return Void
     */
    public static function ExchangePoints($request)
    {
        # 変数の定義
        $user =Auth::user();
        $id_array = $request->user_prize_ids;

        # ポイント交換するユーザー商品を取得
        $user_prizes = UserPrize::where('user_id',$user->id)
        ->where('point_history_id',NULL)//ポイント収支履歴（未交換のみ）
        ->where('shipped_id'      ,NULL)//発送履歴（未交換のみ）
        ->find( $id_array );


        # 交換ポイントの合計($total_point)
        $total_point = 0;
        foreach ($user_prizes as $user_prize) {
            $total_point += $user_prize->point;
        }

        # ポイント履歴の登録
        if( $user_prizes->count()>0 ){
            $point_history = new PointHistory([
                'user_id'   => $user->id,    //ユーザー　リレーション
                'value'     => $total_point, //ポイント数
                'reason_id' => 12, // '商品のポイント交換',
            ]);
            $point_history->save();

        }else{
            $point_history = null;
        }


        # ユーザー取得商品情報の更新
        foreach ($user_prizes as $user_prize) {
            $user_prize->point_history_id = $point_history->id;
            $user_prize->save();
        }

        // 二重送信防止
        $request->session()->regenerateToken();


        return compact('user_prizes','point_history');
    }

}

