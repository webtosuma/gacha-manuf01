<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
/*
|--------------------------------------------------------------------------
| 管理者(Administartor) - 認証
|--------------------------------------------------------------------------
*/
class AdminAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ログイン・ログアウト処理
    |--------------------------------------------------------------------------
    */

        /**
         * ログイン画面の表示(login_form)
         *
         * @return \Illuminate\View\View
        */
        public function login_form()
        {
            # 既にログイン中の時は、homeへリダイレクト
            if( Auth::check() && Auth::user()->admin ){

                return redirect()->route('admin.home');

            }


            # ログインページの表示
            $admin    = Admin::first();
            $email    = config('app.debug') ? $admin->email : '';
            $password = config('app.debug') ? 'password' : '';


            return view('admin_auth.login_form',compact('email','password'));
        }

        /**
         * ログイン処理(login)
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\View\View
        */
        public function login(Request $request)
        {

            # ログイン成功処理（求職者のアカウントが照合された時）
            $remember = true;

            Auth::attempt( $request->only('email','password'), $remember );

            // dd(Auth::check() && Auth::user()->admin);

            if( Auth::check() && Auth::user()->admin )
            {
                # ユーザー情報をセッションに保存
                $request->session()->regenerate();

                # ログイン前に訪れたページがある場合、前のページに戻る
                if(session('before_admin_url'))
                {
                    return redirect( session('before_admin_url') );
                }


                // マイページTOPへリダイレクト
                return redirect()->route('admin.home')
                ->with('alert-success','ログインしました。');

            }


            # [ ログイン失敗の処理 ] -------
            $message = 'メールアドレスかパスワードが間違っています。';//管理者アカウント

            // ログインフォームへ戻る
            return redirect()->route('admin_auth.login_form')
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

            # ログインページへリダイレクト
            return redirect()->route('admin_auth.login_form');
        }




    //
}
