<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
/*
| =============================================
|  取得した商品 API コントローラー
| =============================================
*/
class UserPrizeApiController extends Controller
{
    /**
     * ユーザーの取得積み商品（ポイント交換・発送済みを除く）
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();


        # ユーザーの取得商品情報
        $query = UserPrize::query();

            # 商品情報とのリレーションがあること
            $query->has('prize');

            # ログインユーザーのデータに絞る
            $query->where('user_id',$user->id);

            # ポイント交換ずみのデータを除く
            $query->where('point_history_id',NULL);

            # 発送済みデータを除く
            $query->where('shipped_id',Null);

            # 取得が新しい順
            $query->orderByDesc('created_at');

            # 商品テーブル(prize)とのリレーション
            $query->with(['prize.rank' => function ($query) {

                // prizeテーブルのpointカラムを降順に並び替える
                // $query->orderBy('point', 'desc');

            }]);

        $user_prizes = $query->get();

        # 画像パスの登録
        foreach ($user_prizes as $user_prize) {

            $user_prize->prize->image_path =  $user_prize->prize->image_path;

        }

        return response()->json( $user_prizes );
    }



    /**
     * IDを指定して、ユーザーの取得積み商品取得（ポイント交換・発送済みを除く）
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        $user = Auth::user();
        $id_array = $request->user_prize_ids;//発送するユーザー商品ID

        $query = UserPrize::query();

            # ログインユーザーのデータに絞る
            $query->where('user_id',$user->id);

            # ポイント交換ずみのデータを除く
            $query->where('point_history_id',NULL);

            # 発送済みデータを除く
            $query->where('shipped_id',Null);

            # 取得が新しい順
            $query->orderByDesc('created_at');

            # 商品テーブル(prize)とのリレーション
            $query->with('prize.rank');

        $user_prizes = $query->find($id_array);



        # 画像パスの登録
        foreach ($user_prizes as $user_prize) {
            $user_prize->prize->image_path = $user_prize->prize->image_path;
        }
        return response()->json( $user_prizes );
    }



}
