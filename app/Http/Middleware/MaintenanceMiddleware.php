<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| メンテナンス　ミドルウェア―
|--------------------------------------------------------------------------
*/
class MaintenanceMiddleware
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
        # ログインユーザーがサイト管理者のとき
        if( Auth::check() && Auth::user()->admin ){
            return $next($request);
        }

        # メンテナンス中のとき
        if ( MaintenanceController::isUnderMaintenance()  ) {
            return redirect()->route('maintenance');
        }

        return $next($request);
    }
}
