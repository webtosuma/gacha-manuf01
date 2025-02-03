<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPrize;
use App\Models\PointHistory;
/*
|--------------------------------------------------------------------------
| ユーザーポイント期限切れ対応　ミドルウェア―
|--------------------------------------------------------------------------
*/
class UserPointDeadlineMiddleware
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
        $deadline_date = config('app.user_point_deadline_date');
        if( ! $deadline_date ){ return $next($request); }

        # ポイントのリセットメソッド
        self::resetPointMethod( $user );


        return $next($request);
    }




    /**
     * ポイント交換メソッド
     *
     * @param  User $user ユーザー情報
     * @return Void
    */
    public static function resetPointMethod( $user )
    {
        # ポイントの有効期限が切れていなければ、スキップ
        if( ! $user->is_point_deadline ){ return ; }

        # ポイントが0のとき
        if( $user->point==0 ){ return ; }

        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,      //ユーザーID
            'value'     => - $user->point, //ポイント数
            'reason_id' => 23,             //ポイント期限切れ
        ]);
        $point_history->created_at = $user->point_deadline_at;//登録日を、締め切り期日とする
        $point_history->updated_at = $user->point_deadline_at;//登録日を、締め切り期日とする
        $point_history->save();

    }
}
