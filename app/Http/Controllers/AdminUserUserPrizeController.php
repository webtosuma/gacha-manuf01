<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\UserPrizeDeadLineMiddleware;//ユーザー商品期限切れ対応　ミドルウェア―
use App\Models\User;
use App\Models\UserPrize;
use App\Models\PointHistory;
use App\Models\CanpaingIntroductory;
/*
| =============================================
|  サイト管理者 登録ユーザー [ユーザー商品] コントローラー
| =============================================
*/
class AdminUserUserPrizeController extends Controller
{
    /**
     * ユーザーの取得商品履歴(個人・全体)
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $user_id(0:全て n:個人)
     * @return \Illuminate\Http\Response
    */
    public function index($user_id)
    {
        # ユーザー情報
        $user = $user_id ? User::withTrashed()->find($user_id) : null;//退会者を含む

        # ユーザーの取得商品情報
        $user_prizes = UserPrize::onlyPossessionScope($user_id)
        ->paginate(100);//ページネーション


        # 画像パスの登録
        foreach ($user_prizes as $user_prize) {

            $user_prize->prize->image_path =  $user_prize->prize->image_path;

        }

        return view('admin.user.user_prize.index', compact('user_prizes','user') );
    }



    /**
     * カラム表示
     *
     * @param User $user
     * @return \Illuminate\Http\Response
    */
    public function column(User $user)
    {
        # ユーザーの取得商品情報
        $user_prizes = UserPrize::onlyPossessionScope($user->id)
        ->paginate(100);//ページネーション


        # 画像パスの登録
        foreach ($user_prizes as $user_prize) {

            $user_prize->prize->image_path =  $user_prize->prize->image_path;

        }

        return view('admin.user.user_prize.column', compact('user_prizes','user') );
    }




    /**
     * (API)期限切れユーザー商品のポイント交換
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_change_point()
    {
        # 期限・期限なしのとき
        $deadline_date = config('app.user_prize_deadline_date');//利用可能期間
        if( ! $deadline_date ){ return response()->json( [] ); }//期限なしのとき

        # 期限切れ対象となる「商品の取得日」
        $deadLine_created_at = today()->copy()->subDay($deadline_date-1 );//期限1/10のとき、 ($deadline_date-1)1/11 00:00 以下となる

        # 期限切れのユーザー商品
        $user_prizes = UserPrize::where('created_at','<=',$deadLine_created_at)//期限切れ
        // ->where('point_history_id',NULL)//ポイント収支履歴（未交換のみ）//ページネーションなので、不要
        ->where('shipped_id'      ,NULL)//発送履歴（未交換のみ）
        ->paginate(20);

        # ポイント交換メソッド
        UserPrizeDeadLineMiddleware::changePointMethod( $user_prizes );

        return response()->json( $user_prizes );
    }



    /**
     * ポイント交換完了
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function comp_change_point( Request $request )
    {
        return redirect()->route('admin.user.other_menu')
        ->with(['alert-success'=>'一括期限切れ処理が完了しました。']);
    }



}
