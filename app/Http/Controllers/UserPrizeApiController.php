<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserPrize;
use App\Models\Prize;
use App\Models\User;
use App\Models\GachaCategory;
use App\Models\PointHistory;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user_id ? User::find($request->user_id) : Auth::user();


        # カテゴリー
        $categories = [ new GachaCategory(['name'=>'全て', 'id'=>0]) ];
        $get_categories = GachaCategory::userList()->get();
        foreach ($get_categories as $get_category) { $categories[] = $get_category; }

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


            # 商品テーブル(prize)とのリレーション
            $query->with(['prize.rank' => function ($query) {

                // prizeテーブルのpointカラムを降順に並び替える
                // $query->orderBy('point', 'desc');

            }]);

            # カテゴリーの選択
            if(  $request->category_id ){
                # 商品ID配列
                $prizeIdArray = Prize::where('category_id',$request->category_id)->get()->pluck('id')->toArray();
                $query->whereIn('prize_id',$prizeIdArray);
            }

            # 並び替え
            $order = $request->order;
            switch ($order) {

                # 高いポイント順
                case 'desc_point':
                    $query->orderByDesc('point')
                    ->orderByDesc('prize_id')
                    ->orderByDesc('created_at');
                    break;

                # 低いポイント順
                case 'asc_point':
                    $query->orderBy('point')
                    ->orderByDesc('prize_id')
                    ->orderByDesc('created_at');
                    break;

                # 取得が古い順
                case 'asc_created':
                    $query->orderBy('created_at');
                    break;

                # 取得が新しい順
                default:
                    $query->orderByDesc('created_at');
                    break;
                //
            }

            # 商品名検索
            $search_key = $request->search_key;
            if($search_key){
                $prize_ids = Prize::where('name','like','%'.$search_key.'%')->get()->pluck('id')->toArray();
                $query->whereIn('prize_id',$prize_ids);
            }

        // $user_prizes = $query->get();


        $user_prizes = $query->paginate(100);


        # 追加データ
        foreach ($user_prizes as $user_prize) {
            $user_prize->prize->image_path = $user_prize->prize->image_path; //画像パスの登録
            $user_prize->deadline_text     = $user_prize->deadline_text;     //有効期限テキスト
        }

        // return response()->json( $user_prizes );
        return response()->json( compact('categories','user_prizes') );

    }



    /**
     * 商品IDを指定して、ユーザーの取得積み商品取得（ポイント交換・発送済みを除く）
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


    /**
     * API商品のポイント交換
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exchange_points(Request $request)
    {
        # 変数の定義
        $user =Auth::user();
        $user_prize_ids = $request->user_prize_ids;

        # ポイント交換するユーザー商品を取得(発送済み以外)
        $user_prizes = UserPrize::where('user_id',$user->id)
        // ->where('point_history_id',NULL)//ポイント収支履歴（未交換のみ）
        ->where('shipped_id'      ,NULL)//発送履歴（未交換のみ）
        ->whereIn('id',$user_prize_ids )
        ->paginate(20);


        DB::beginTransaction();
        try {

            foreach ($user_prizes as $user_prize)
            {
                # 処理済の時はスキップ
                if($user_prize->point_history_id){ continue; }

                # ポイント履歴の登録
                $point_history = new PointHistory([
                    'user_id'   => $user->id,    //ユーザー　リレーション
                    'value'     =>  $user_prize->point, //ポイント数
                    'reason_id' => 12, // '商品のポイント交換',
                ]);
                $point_history->save();

                # ユーザー取得商品情報の更新
                $user_prize->point_history_id = $point_history->id;
                $user_prize->save();
            }


            DB::commit();
        } catch (\Exception $e) {

            Log::error($e);
            DB::rollback();
            return redirect()->json(['message'=>'エラーが発生しました。'],500);

        }

        return response()->json( $user_prizes );
    }

}
