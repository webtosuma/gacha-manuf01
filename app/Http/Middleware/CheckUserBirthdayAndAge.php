<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
/*
|--------------------------------------------------------------------------
| 年齢チェックミドルウェア―
| 1.誕生日情報が未入力のときに、誕生日登録フォームへリダイレクト
| 2.年齢が指定年齢以下のときにはページを表示できませんページへリダイレクト
|--------------------------------------------------------------------------
*/

class CheckUserBirthdayAndAge
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {

        # 年齢制限の指定がなければスルー
        $minAge = config('app.min_age', false); // デフォルト18歳など
        if (!$minAge) { return $next($request); }

        # 未ログインならスルー
        $user = Auth::user();
        if (!$user) { return $next($request); }

        # ① 誕生日未登録 -> 登録フォームへリダイレクト
        if (empty($user->birthday)) {
            return redirect()->route('settings.age.birthday');
        }

        # ② 年齢チェックNG
        $age = Carbon::parse($user->birthday)->age;
        if ($age < $minAge) {
            return redirect()->route('settings.age.restrictedy');
        }


        return $next($request);
    }
}
