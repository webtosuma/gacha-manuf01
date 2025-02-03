<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\UserPointDeadlineMiddleware;//ユーザーポイント期限切れ対応　ミドルウェア―
use App\Models\User;
use App\Models\UserPrize;
use App\Models\UserShipped;
use App\Models\PointHistory;
use App\Models\CanpaingIntroductory;
/*
| =============================================
|  サイト管理者 登録ユーザー [ポイント履歴] コントローラー
| =============================================
*/
class AdminUserPointHistoryController extends Controller
{
    /**
     * ポイント履歴(個人・全体)
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $user_id(0:全て n:個人)
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $user_id)
    {
        # ユーザー情報
        $user = $user_id ? User::withTrashed()->find($user_id) : null;//退会者を含む

        # ポイントの入出理由　絞り込み
        $reason_id = $request->reason_id ?? 0;
        $reason_id = !$request->page ? $reason_id : Session::get('user.point_history.reason_id');//ページネーション対応
        Session::put('user.point_history.reason_id', $reason_id);//セッションに保存


        # ポイント履歴の取得
        $query = PointHistory::query();

            if($user){
                $query->where('user_id', $user->id);
            }
            if($reason_id){
                $query->where('reason_id', $reason_id);
            }

            $query->orderByDesc('created_at')->orderByDesc('id');

        $point_histories = $query->paginate(100);//ページネーション




        # ポイントの入出理由　一覧
        $reasons   = PointHistory::reasons();


        return view('admin.user.point_history.index', compact('point_histories','user','reasons','reason_id') );
    }



    /**
     * ポイント履歴削除確認（ユーザー指定）
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $user_id(0:全て n:個人)
     * @return \Illuminate\Http\Response
    */
    public function destroy_confirm(Request $request, $user_id)
    {


        # ユーザー情報
        $user = $user_id ? User::find($user_id) : null;

        # 削除するポイント履歴ID
        $point_history_ids = $request->point_history_ids;

        if( !isset($point_history_ids) ){
            return back()->with(['alert-danger'=>'削除するポイント履歴が選択されていません。','icon'=>'bi-question-lg']);
        }

        $point_histories = PointHistory::orderByDesc('created_at')->orderByDesc('id')
        ->whereIn('id',$point_history_ids)//「ポイント購入」を除く
        ->get();

        // dd($point_histories->toArray());

        return view('admin.user.point_history.destroy_confirm', compact('point_histories','user') );
    }




    /**
     * ポイント履歴削除（ユーザー指定）
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $user_id(0:全て n:個人)
     * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request,$user_id)
    {

        # 削除するポイント履歴データの取得
        // $point_histories = PointHistory::orderByDesc('created_at')->orderByDesc('id')
        // ->find($request->point_history_ids);
        $point_histories = PointHistory::find($request->point_history_ids);

        foreach ($point_histories as $point_history) {


            switch ( $point_history->reason_id ) {

                case 12://商品のポイント交換

                    # 商品のポイント交換履歴の処理
                    self::DeleteExchangePointHistory($point_history);
                    break;


                case 16://商品の取得期限切れによるポイント交換

                    # 商品のポイント交換履歴の処理
                    self::DeleteExchangePointHistory($point_history);
                    break;


                case 22://商品発送

                    # 発送履歴の処理
                    self::DeletePrizeShippedHistory($point_history);
                    break;


                case 21://ガチャPLAY

                    # ガチャ履歴の処理
                    self::DeleteGachaHistory($point_history);
                    break;


                default:

                    # ポイント履歴の削除
                    $point_history->delete();
                    break;


                //

            }


        }


        return redirect()->route('admin.user.point_history',$user_id)
        ->with(['alert-danger'=>'ポイント履歴を削除しました。']);
    }



        /** 12.ポイント交換履歴の削除 */
        public function DeleteExchangePointHistory($point_history)
        {

            if( $point_history->user_prizes )
            {
                # ユーザー商品から、ポイント履歴を削除
                $user_prizes = $point_history->user_prizes;
                foreach ($user_prizes as $user_prize) {
                    $user_prize->update(['point_history_id'=>null]);//ポイント交換履歴のリセット
                }


                # ポイント履歴の削除
                $point_history->delete();
            }
        }


        /** 22.商品発送履歴の削除 */
        public function DeletePrizeShippedHistory($point_history)
        {
            $bool = false;

            if(
                $point_history->user_shipped
                && !$point_history->user_shipped->shipment_at //発送ずみではないとき(発送済みの時は削除できない)
            )
            {
                # 発送申請したユーザー商品から、発送履歴を削除
                $user_prizes = $point_history->user_shipped->user_prizes;
                foreach ($user_prizes as $user_prize) {
                    $user_prize->update(['shipped_id'=>null]);//発送履歴のリセット
                }

                # 発送履歴のリセット
                $point_history->user_shipped->delete();

                # ポイント履歴の削除
                $point_history->delete();

                # 処理実行の有無
                $bool = true;
            }

            return $bool;
        }


        /** 21.ガチャ履歴の削除 */
        public function DeleteGachaHistory($point_history)
        {
            if( $point_history->user_gacha_history )
            {
                ## ガチャで取得した「ユーザー商品」を削除
                $user_prizes = $point_history->user_gacha_history->user_prizes;
                foreach ($user_prizes as $user_prize) {


                    # ポイント交換積みの商品があるとき
                    if($user_prize->point_history_id)
                    {
                        // ユーザー商品のポイント交換履歴
                        $ex_point_history = PointHIstory::find($user_prize->point_history_id);

                        # 12. 商品のポイント交換履歴の処理
                        if($ex_point_history){
                            self::DeleteExchangePointHistory($ex_point_history);
                        }

                        # ユーザー商品を削除
                        $user_prize->delete();
                    }


                    # 発送依頼ずみ商品があるとき
                    else if($user_prize->shipped_id)
                    {
                        $user_shipped  = UserShipped::find($user_prize->shipped_id);
                        $shipped_point_history = $user_shipped ? PointHIstory::find($user_shipped->point_history_id) : NULL;
                        if($shipped_point_history){

                            # 22. 発送履歴の処理
                            $bool = self::DeletePrizeShippedHistory($shipped_point_history);

                            # ユーザー商品を削除(商品が発送済みの場合を除く)
                            if($bool){ $user_prize->delete(); }

                        }
                    }


                    # その他
                    else
                    {
                        $user_prize->delete();
                    }
                }


                ## ガチャ履歴を削除
                $user_gacha_history = $point_history->user_gacha_history;
                $user_gacha_history->delete();


                ## ポイント履歴の削除
                $point_history->delete();
            }

        }



    /*
    | ------------------------------------
    |  ユーザーポイントの期限切れ
    | ------------------------------------
    */

        /**
         * (API)期限切れユーザーポイントのリセット
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
        */
        public function api_point_reset()
        {
            # 期限・期限なしのとき
            $deadline_date = config('app.user_point_deadline_date');
            if( ! $deadline_date ){ return response()->json( [] ); }

            # ユーザー情報
            $users = User::paginate(20);


            # ポイントのリセットメソッド
            foreach ($users as $user) {
                UserPointDeadlineMiddleware::resetPointMethod( $user );
            }

            return response()->json( $users );
        }



        /**
         * リセット完了
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function comp_point_reset( Request $request )
        {
            return redirect()->route('admin.user.other_menu')
            ->with(['alert-success'=>'ユーザーの期限切れポイントを、すべてリセットしました。']);
        }



    /* */
}
