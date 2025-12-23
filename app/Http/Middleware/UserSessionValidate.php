<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  1アカウント1ログイン(セッションIDチェック)　ミドルウェアー
| =============================================
*/
class UserSessionValidate
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
        # ログインアカウント
        $user = Auth::check() ? Auth::user() : null ;

        # セッションIDチェック
        if(
            $user//ログイン中のみ

            // && !$user->admin //サイト管理者を除外

            && $user->current_session_id !== session()->getId() //セッションIDが一致しない

            && env('APP_DEBUG') === false//テストモードを除外
        ){
            Auth::logout();//ログアウト
        }


        return $next($request);
    }
}
