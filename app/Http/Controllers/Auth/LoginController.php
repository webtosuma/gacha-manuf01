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
use App\Models\Admin;
use App\Models\User;

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
     * ログインフォーム
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # ログイン中の時は、トップへリダイレクト
        if(Auth::check()){
            return redirect()->route('home');
        }


        # ログインページの表示
        $email    = '';
        $password = '';
        return view('auth.login',compact('email','password'));
    }




    /**
     * ログイン処理(login)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function login(Request $request)
    {
        # ユーザーアカウント
        $user = User::where('email', $request->email)->first();

        # 失敗回数のリセット
        $AgoeHoursAgo = now()->subHours( (Int) self::MaxFreezingHours() );
        if(
            $user && $user->tfa_failures_at && $user->tfa_failures_at < $AgoeHoursAgo//凍結時間内
        ){
            $user->tfa_failures_count = 0;
            $user->tfa_failures_at    = null;
            $user->save();
        }


        # 失敗回数の保存
        if( $user &&  !Hash::check( $request->password, $user->password) )
        {
            $user->tfa_failures_count += 1;
            $user->tfa_failures_at     = now();
            $user->save();
        }


        # ログイン凍結レスポンス
        $AgoeHoursAgo = now()->subHours( (Int) self::MaxFreezingHours() );
        if(
            $user
            && $user->tfa_failures_count > (Int) self::MaxTfaFailuresCount() //最大カウントを超える
            && $user->tfa_failures_at > $AgoeHoursAgo//凍結時間内
        ){
            $message = 'ログインの試行回数が上限に達しました。一定時間経過後に、再度ログインをお試しください';
            return redirect()->route('login')
            ->with('login_error',$message)
            ->with('email',$request->email)
            ->with('password',$request->password);
        }


        // # Adminチェック(TFA認証時はスキップ)
        // if($user && $user->admin && !$request->tfa_key==$user->tfa_key)
        // {
        //     $message = '先ほどのメールアドレスは、こちらのページからログインすることができません。';
        //     return redirect()->route('login')
        //     ->with('login_error',$message)
        //     ->with('email',$request->email)
        //     ->with('password',$request->password);
        // }


        # ログイン成功処理（求職者のアカウントが照合された時）
        $remember = true; //$request->remember(ログイン状態の維持)
        Auth::attempt( $request->only('email','password'), $remember );

        # ログイン前に訪れたページ
        $before_url = $request->session()->get( 'before_url') ;

        # ログイン処理
        if( Auth::check() ){

            # ログイン成功処理（求職者のアカウントが照合された時）
            // ユーザー情報をセッションに保存
            $request->session()->regenerate();


            # セッションIDの保存
            $user = Auth::user();
            $user->current_session_id = session()->getId();
            $user->save();


            # ログイン前に訪れたページがある場合、前のページに戻る
            if($before_url)
            {
                return redirect( $before_url )
                ->with('alert-primary','ログインしました。');
            }


            // マイページTOPへリダイレクト
            return
            redirect()->route('home')
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

        /** 最大失敗回数 */
        public static function MaxTfaFailuresCount(){ return 3; }

        /** 最大凍結時間 */
        public static function MaxFreezingHours(){ return 3; }


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
        // return redirect()->route('home')
        // ->with('alert-secondary','ログアウトしました。');

        return redirect()->route('home')
        ->with('alert-secondary','ログアウトしました。');

    }

}
