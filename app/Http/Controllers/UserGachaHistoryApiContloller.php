<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use App\Models\PointHistory;
/*
| =============================================
|  ガチャ履歴 API コントローラー
| =============================================
*/
class UserGachaHistoryApiContloller extends Controller
{
    /**
     * ユーザーのガチャ履歴詳細
     *
     * @param \Illuminate\Http\Request $request
     * @param App\Models\UserGachaHistory $user_gacha_history
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request, UserGachaHistory $user_gacha_history )
    {

        # ユーザーの取得商品情報
        $query = UserPrize::query();

            # ポイント交換を伴うとき
            if($request->show_change_btn){

                # ログインユーザーのデータに絞る
                $user = Auth::user();
                $query->where('user_id',$user->id);

                # ポイント交換ずみのデータを除く
                $query->where('point_history_id',NULL);

                # チケット交換ずみのデータを除く
                $query->where('to_ticket_history_id',NULL);

                # 発送済みデータを除く
                $query->where('shipped_id',Null);

            }

            # 商品テーブル(prize)とのリレーション
            $query->with(['prize.rank' => function ($query) {
                $query->orderBy('order', 'desc');
            }]);

            # ポイント順、商品ID順
            $query->orderByDesc('point');
            $query->orderByDesc('prize_id');
            $query->orderByDesc('id');
            $query->orderByDesc('created_at');


            # 指定した『ガチャ履歴』に該当する商品のみ
            $query->where('gacha_history_id', $user_gacha_history->id);

        $userPrizes = $query->paginate( 12 );


        # 画像パスの登録
        foreach ($userPrizes as $user_prize) {
            $user_prize->prize->image_path = $user_prize->prize->image_path;
        }

        return response()->json( compact('userPrizes') );
    }
}
