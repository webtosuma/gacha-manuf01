<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccessLog;
use App\Http\Controllers\Method;
/*
|--------------------------------------------------------------------------
| アクセスログ ミドルウェア―
|--------------------------------------------------------------------------
*/
class AccessLogMiddleware
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

        # ログの利用
        $bool = \App\Http\Controllers\AdminAccessLogController::logStartupSetting();

        # ログイン中のみ、ログの保存
        if ( Auth::check() && !str_contains($request->fullUrl(),'api') && $bool )
        {

            # ユーザーエージェント
            $new_text = $request->header('User-Agent');//新しい入力テキスト
            $dir = 'upload/admin/access_log';      //保存先ディレクトリ
            $user_agent = Method::uploadStorageText($dir, $new_text, null);

            # ログの保存
            $accessLog = new AccessLog([
                'user_id'    => Auth::id(),
                'ip'         => $request->ip(),
                'path'       => $request->fullUrl(),
                'user_agent' => $user_agent,
            ]);
            $accessLog->save();
        }

        return $next($request);
    }
}
