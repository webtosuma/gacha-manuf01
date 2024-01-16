<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\PointHistory;
use App\Models\CanpaingIntroductory;
/*
| =============================================
|  [キャンペーン]　お友達紹介 コントローラー
| =============================================
*/
class CanpaingIntroductoryController extends Controller
{
    /** 付与するポイント */
    public static function grantPoint(){
        return 1000;
    }


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
     * URLページの表示
    */
    public function index()
    {

        # キャンペーンが実施されてるかチェック
        if( !$this->active() ){ return \App::abort(404); }

        # 変数
        $key = $this->createKey( Auth::user() );
        $url = route('canpaing.introductory.register',$key);

        return  view('canpaing.introductory', compact('url'));
    }




    /**
     * 会員登録ページの表示(お友達紹介から)
     *
     * @param \Illuminate\Http\Request $request
     * @param  String $key //紹介キャンペーン キー
     * @return \Illuminate\View\View
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
     * @param \Illuminate\Http\Request $request
     * @param User $friend //紹介した友達のID
     * @return Void
    */
    public static function createRecord( $request, $friend )
    {
        # セッション情報の取得
        $recruiter_id = $request->session()->get('recruiter_id');

        # 紹介ユーザー情報の取得
        $recruiter = User::find($recruiter_id);

        # ユーザー紹介履歴の登録
        if($recruiter){
           $canpaing_introductory = new CanpaingIntroductory([
                'recruiter_id' => $recruiter->id,//勧誘ユーザーのID
                'friend_id'    => $friend->id,   //紹介した友達のID
            ]);
           $canpaing_introductory->save();

        }
    }



    ## [お友達紹介]ポイント付与
    public static function grant($user)
    {
        $point = self::grantPoint();//付与ポイント

        # ユーザー紹介履歴(お友達側)の取得
        $canpaing_introductory = CanpaingIntroductory::where('friend_id',$user->id)->first();
        // dd($canpaing_introductory);

        # POINT履歴：紹介キャンペーン：新規登録ユーザー(32)
        $reason_id = 32;
        $canpaing_friend_count = PointHistory::where('user_id',$user->id)
        ->where('reason_id',$reason_id)->get()->count();



        # 紹介ユーザーとお友達ユーザーへポイント付与
        if(
            $canpaing_introductory &&    // ユーザー紹介履歴(お友達側)があるか
            $canpaing_friend_count ==0   // キャンペーン付与（お友達側)履歴なし
        ){
            // ユーザー紹介履歴の更新
            $now = now();
            $canpaing_introductory->update(['done_at'=>$now]);



            // 紹介者ポイント付与
            $user_id   = $canpaing_introductory->recruiter_id;
            $reason_id = 31;//お友達紹介キャンペーン：紹介者付与

            $point_history = new PointHistory([
                'user_id'   => $user_id,          //ユーザー　リレーション
                'value'     => $point, //ポイント数
                'reason_id' => $reason_id, //'お友達紹介キャンペーン'
                'created_at'=> $now,
                'updated_at'=> $now,
            ]);
            $point_history->save();


            // お友達ポイント付与
            $user_id   = $canpaing_introductory->friend_id;
            $reason_id = 32;//お友達紹介キャンペーン：紹介登録者付与

            $point_history = new PointHistory([
                'user_id'   => $user_id,          //ユーザー　リレーション
                'value'     => $point, //ポイント数
                'reason_id' => $reason_id, //'お友達紹介キャンペーン'
                'created_at'=> $now,
                'updated_at'=> $now,
            ]);
            $point_history->save();



            # 紹介者へメール送信
            $recruiter = User::find($canpaing_introductory->recruiter_id);
            $friend    = User::find($canpaing_introductory->friend_id);
            Mail::to( $recruiter->email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs' => ['recruiter'=>$recruiter, 'friend'=>$friend] , //入力変数
                'view' => 'emails.canpaing.introductory_friend_register' , //テンプレート
                'subject' => '紹介したお友達の会員登録が完了いたしました。' , //件名
            ]) );

        }

    }

}
