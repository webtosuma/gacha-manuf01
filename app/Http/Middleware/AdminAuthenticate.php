<?php

namespace App\Http\Middleware;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| 管理者(admin)認証チェックミドルウェア―
|--------------------------------------------------------------------------
*/
class AdminAuthenticate extends Middleware
{
    /**
     *  ログインしていないとき、「管理者ログインページへリダイレクト
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$guards)
    {
        # TESTユーザーとしてログイン
        // if(
        //     env('APP_TEST_AUTH') &&
        //     !( Auth::check() && Auth::user()->admin )
        // )
        // {
        //     $user = \App\Models\Administrator::first()->user;
        //     $param = [ 'email'=>$user->email, 'password'=>'password' ];
        //     if( Auth::attempt( $param ) ) {  $request->session()->regenerate(); }
        // }


        # 管理者アカウント(admin)でログイン中ではないとき、リダイレクト
        if ( !( Auth::check() && Auth::user()->admin ) )
        {
            # 前ページのURLをセッションに保存
            $before_admin_url = $request->path();
            $request->session()->put( 'before_admin_url', $before_admin_url);


            # 管理者ログインページへリダイレクト
            return redirect()->route('admin_auth.login_form');
        }


        return $next($request);
    }

}
