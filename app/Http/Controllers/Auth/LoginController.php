<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * ログイン処理(login)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function login(Request $request)
    {

        # ログイン成功処理（求職者のアカウントが照合された時）
        $remember = true; //$request->remember(ログイン状態の維持)
        Auth::attempt( $request->only('email','password'), $remember );

        # ログイン処理
        if( Auth::check() ){

            # ログイン成功処理（求職者のアカウントが照合された時）
            // ユーザー情報をセッションに保存
            $request->session()->regenerate();

            # ログイン前に訪れたページがある場合、前のページに戻る
            if(session('before_worker_url'))
            {
                return redirect( session('before_worker_url') )
                ->with('alert-primary','ログインしました。');
            }


            // マイページTOPへリダイレクト
            return redirect()->route('home')
            ->with('alert-primary','ログインしました。');

        }

        # [ ログイン失敗の処理 ] -------
        $message = 'メールアドレスかパスワードが間違っています。';//管理者アカウント

        // ログインフォームへ戻る
        return redirect()->route('login')
        ->with('login_error',$message)
        ->with('email',$request->email)
        ->with('password',$request->password);

    }




    /**
     * ログアウト処理(logout)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function logout(Request $request)
    {
        Auth::logout(); //ユーザーセッションの削除

        $request->session()->invalidate(); //全セッションの削除

        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)

        # へリダイレクト
        return redirect()->route('home')
        ->with('alert-secondary','ログアウトしました。');
    }

}
