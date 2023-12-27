<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
/*
| =============================================
|  [キャンペーン]　お友達紹介 コントローラー
| =============================================
*/
class CanpaingIntroductoryController extends Controller
{

    /** キャンペーンが実施されてるか */
    public static function active(){
        return true;
        // return false;
    }


    /** 紹介ユーザー認証キーの生成 */
    public static function createKey($user){
        return  $user->id.'-'.$user->created_at->format('ymdHis');
    }


    /**
     * 紹介ユーザー認証キーの認証チェック
     * @return User $usr
     */
    public static function usrKeyCheck($key){

        # キーの型チェック
        $pattern = '/^\d+-\d{12}$/';
        if( !preg_match($pattern, $key) ){ return NULL; }

        # 変数定義
        list($user_id,$dateTimeString) = explode('-',$key);

        # モデルデータが存在するか
        $user = User::find($user_id);

        # 登録日認証
        return $user && $user->created_at->format('ymdHis') == $dateTimeString ? $user : NULL ;
    }


    /**
     * 表示
    */
    public function index()
    {
        # キャンペーンが実施されてるかチェック
        if( !$this->active() ){ return \App::abort(404); }

        # 変数
        $key = $this->createKey( Auth::user() );
        $url = route('canpaing.introductory.register',$key);

        #
        // $user = $this->usrKeyCheck($key);
        // dd($user);

        return  view('canpaing.introductory', compact('url'));
    }




    /**
     * 会員登録ページの表示(前処理)
     * @param \Illuminate\Http\Request $request
     * @param  String $key //紹介キャンペーン キー
     * @return RegisterController::showRegistrationForm()
    */
    public function register(Request $request, $key=null )
    {
        # 紹介キャンペーン キーの認証
        $user = $this->usrKeyCheck($key);

        # 認証できないキャンペーンキーのとき
        if(
            !$this->active() || ( $key && !$user ) //認証できないキャンペーンキー
        ){ return \App::abort(404); }


        # 紹介者のuser_idをセッションへ保存
        $request->session()->put('recruiter_id',$user->id);


        return view('auth.register');
    }



    /**
     * ユーザー紹介履歴の登録
    */
    public static function createCanpaingIntroductoryRecord()
    {
        # 紹介者情報の取得
        $recruiter_id = $request->session()->get('recruiter_id');
        // $recruiter = User
    }
}
