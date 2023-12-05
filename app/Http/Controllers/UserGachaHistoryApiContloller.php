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
     * @param App\Models\UserGachaHistory $user_gacha_history
     * @return \Illuminate\Http\Response
     */
    public function show(UserGachaHistory $user_gacha_history)
    {
        # ユーザー以外の履歴は非表示
        $user = Auth::user();
        if( $user->id == $user_gacha_history->id ){ return response()->json( [], 401 ); }


        # ユーザーの取得商品情報
        $query = UserPrize::query();

            # ログインユーザーのデータに絞る
            $user = Auth::user();
            $query->where('user_id',$user->id);

            # ポイント交換ずみのデータを除く
            $query->where('point_history_id',NULL);

            # 発送済みデータを除く
            $query->where('shipped_id',Null);


            # 商品テーブル(prize)とのリレーション
            $query->with(['prize.rank' => function ($query) {
                // prizeテーブルのpointカラムを降順に並び替える
                // $query->orderBy('point', 'desc');
            }]);

            # 指定した『ガチャ履歴』に該当する商品のみ
            $query->where('gacha_history_id', $user_gacha_history->id);

        $user_prizes = $query->get();


        # 画像パスの登録
        foreach ($user_prizes as $user_prize) {
            $user_prize->prize->image_path = $user_prize->prize->image_path;
        }

        return response()->json( $user_prizes );





        //

        $user_gacha_history = UserGachaHistory::with('user_prizes.prize')->find($user_gacha_history->id);

        $count = $user_gacha_history->user_prizes->count();
        for ($i=0; $i < $count; $i++) {
            $image_path = $user_gacha_history->user_prizes[$i]->prize->image_path;
            $user_gacha_history->user_prizes[$i]->prize->image_path = $image_path;
        }


        return response()->json( $user_gacha_history );
    }
}
