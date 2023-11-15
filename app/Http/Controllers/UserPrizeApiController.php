<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
/*
| =============================================
|  取得した景品 API コントローラー
| =============================================
*/
class UserPrizeApiController extends Controller
{
    /**
     * ユーザーの取得積み景品（ポイント交換・発送済みを除く）
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # ユーザーの取得景品情報
        $query = UserPrize::query();

            # ログインユーザーのデータに絞る
            $user = Auth::user();
            $query->where('user_id',$user->id);

            # ポイント交換ずみのデータを除く
            $query->where('point_history_id',NULL);

            # 発送済みデータを除く
            $query->where('shipped_id',Null);

            # 取得が新しい順
            $query->orderByDesc('created_at');

            # 景品テーブル(prize)とのリレーション
            $query->with(['prize' => function ($query) {

                // prizeテーブルのpointカラムを降順に並び替える
                // $query->orderBy('point', 'desc');

            }]);

        $user_prizes = $query->get();
        foreach ($user_prizes as $user_prize) {
            $user_prize->prize->image_path = $user_prize->prize->image_path;
        }

        return response()->json( $user_prizes );
    }
}
