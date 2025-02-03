<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
use App\Models\PointHistory;
/*
|--------------------------------------------------------------------------
| ユーザー商品期限切れ対応　ミドルウェア―
|--------------------------------------------------------------------------
*/
class UserPrizeDeadLineMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        # ログイン中でなければ、処理をスキップ
        if( ! Auth::check() ){ return $next($request); }

        # ユーザー情報
        $user = Auth::user();

        # 期限・期限なしのとき
        $deadline_date = config('app.user_prize_deadline_date');//利用可能期間
        if( ! $deadline_date ){ return $next($request); }//期限なしのとき

        # 期限切れ対象となる「商品の取得日」
        $deadLine_created_at = today()->copy()->subDay($deadline_date-1 );//期限1/10のとき、 ($deadline_date-1)1/11 00:00 以下となる

        # 期限切れのユーザー商品
        $user_prizes = UserPrize::where('created_at','<=',$deadLine_created_at)//期限切れ
        ->where('user_id',$user->id)    //ログインユーザーの取得商品
        ->where('point_history_id',NULL)//ポイント収支履歴（未交換のみ）
        ->where('shipped_id'      ,NULL)//発送履歴（未交換のみ）
        ->get();

        # ポイント交換メソッド
        self::changePointMethod( $user_prizes );

        return $next($request);
    }



    /**
     * ポイント交換メソッド
     *
     * @param  UserPrize $user_prizes 期限切れのユーザー商品(ポイント交換済みも含む)
     * @return UserPrize $user_prizes
    */
    public static function changePointMethod( $user_prizes )
    {
        foreach ($user_prizes as $user_prize)
        {
            # ポイント交換済みはスキップ
            if( $user_prize->point_history_id ){ continue; }

            # ポイント履歴の登録
            $point_history = new PointHistory([
                'user_id'   => $user_prize->user->id, //ユーザー　リレーション
                'value'     => $user_prize->point, //ポイント数
                'reason_id' => 16, // 期限切れによるポイント交換
            ]);
            $point_history->created_at = $user_prize->deadline_at;//登録日を、締め切り期日とする
            $point_history->updated_at = $user_prize->deadline_at;//登録日を、締め切り期日とする
            $point_history->save();

            # ユーザー取得商品情報の更新
            $user_prize->point_history_id = $point_history->id;
            $user_prize->save();

        }

        return $user_prizes;
    }
}
