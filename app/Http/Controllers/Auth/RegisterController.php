<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SendMailController;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CanpaingIntroductoryController;//お友達紹介キャンペーン
/*
|--------------------------------------------------------------------------
| 会員登録 Controller
|--------------------------------------------------------------------------
*/
class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
    */
    protected $redirectTo = RouteServiceProvider::HOME;



    /**
     * 会員登録
     *
     * @param \Illuminate\Http\UserRegisterRequest $request
     * @return JSON
    */
    public function index(Request $request)
    {

        # ログイン中の時は、トップへリダイレクト
        if(Auth::check()){
            return redirect()->route('home');
        }


        # IPアドレスのチェック
        $ip_check = self::ipCheck($request);


        return view('auth.register',compact('ip_check'));
    }

    /**
     * 会員登録処理
     *
     * @param \Illuminate\Http\UserRegisterRequest $request
     * @return JSON
    */
    public function register_post(UserRegisterRequest $request)
    {

        # 求職者情報の保存
        $user = new \App\Models\User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make( $request->password ),
        ]);
        $user->save();


        # [紹介キャンペーン]ユーザー紹介履歴の登録
        CanpaingIntroductoryController::createRecord( $request, $user );


        # 求職者・管理者へメール送信
        SendMailController::UserAuthRegiste( $user );


        # ログイン
        Auth::attempt( $request->only('email','password'), true );
        $request->session()->regenerate();


        # リダイレクト
        return redirect()->route('register.comp')
        ->with(['alert_register'=>'会員登録が完了しました。']);
    }



    /**
     * 会員登録完了
     *
     * @param \Illuminate\Http\UserRegisterRequest $request
     * @return JSON
    */
    public function comp(Request $request)
    {
        return view('auth.register_comp');
    }



    /**
     * ユーザー登録(step01) API
     *
     * @param \Illuminate\Http\UserRegisterRequest $request
     * @return JSON
    */
    public function step01(UserRegisterRequest $request)
    {
        # 認証番号メールの送信(メール送信は1回のみ)
        $verification_code = SendMailController::UserAuthRegister01( $request );
        // $verification_code ="123456";

        return response()->json([ 'verification_code'=>$verification_code, ]);
    }




    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }




    /** IPアドレスファイルの保存パス */
    public function ipPath(){
        return 'upload/auth/register/ips.json';
    }


    /** IPアドレスの保存 */
    public function ipPost($request)
    {
        $path = self::ipPath();//ファイルパス
        $ip = $request-> ip();//ユーザーのIPアドレス

        # IPアドレス配列の取得
        $array = Storage::exists($path)
        ? json_decode( Storage::get($path) ) : [];

        # IPアドレスの保存
        $array[] = $ip;
        $jsonData = json_encode($array);
        Storage::put($path,$jsonData);

    }



    /** IPアドレスのチェック */
    public function ipCheck($request)
    {
        $path = self::ipPath();//ファイルパス
        $ip = $request-> ip();//ユーザーのIPアドレス

        # IPアドレス配列の取得
        $array = Storage::exists($path)
        ? json_decode( Storage::get($path) ) : [];

        $bool = false;// IPアドレスが登録ずみか否か
        if( in_array($ip, $array) ){ $bool = true; }


        // dd( $bool );
        return $bool;
    }



    public function ipTest(Request $request)
    {

        $path = self::ipPath();//ファイルパス
        $ip = $request-> ip();//ユーザーのIPアドレス

        # IPアドレス配列の取得
        $array = Storage::exists($path)
        ? json_decode( Storage::get($path) ) : [];

        return $array;
    }
}
