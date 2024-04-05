<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserRankHistoryController;
/*
| =============================================
|  会員ランク更新　ミドルウェアー
| =============================================
*/
class UserRankMiddleware
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

        # ログイン中 && チケットシステム導入時のみ
        if( Auth::check() && env('NEW_TICKET_SISTEM',false) )
        {
            $user = Auth::user();
            UserRankHistoryController::CreateUserRankHistory( $user );
        }

        return $next($request);
    }


}
