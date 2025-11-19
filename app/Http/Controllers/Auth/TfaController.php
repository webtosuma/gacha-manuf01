<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Admin; 
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Auth 二段階認証ログイン(TFA) コントローラー
|--------------------------------------------------------------------------
*/
class TfaController extends Controller
{
    /** 最大失敗回数 */
    public static function MaxTfaFailuresCount(){ return 3; }

    /** 最大凍結時間 */
    public static function MaxFreezingHours(){ return 3; }

    /**
     * パスワード認証 API
     *
     * @param \Illuminate\Http\Request $request
     * @return JSON
    */
    public function api_login_password(Request $request)
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
            return response()->json([
                'valid'       => false,  //
                'max_failures'=> true,   //
                'err_message'
                => 'ログインの試行回数が上限に達しました。一定時間経過後に、再度ログインをお試しください',
            ], 200);
        }


        # 失敗レスポンス
        if( !( $user && Hash::check( $request->password, $user->password) ) )
        {
            return response()->json([
                'valid'       => false,
                'err_message' => 'メールアドレスかパスワードが間違っています。',
            ], 200);
        }


        # TFA認証キーの保存
        $tfa_key = '';
        while (!preg_match('/\d/', $tfa_key)){ $tfa_key = Str::random(8); }; // 数字が含まれているかチェック
        $user->tfa_key = $tfa_key;
        $user->save();


        # TFA認証キーの送信
        self::sendTFAKeyMail($user);

        # 2段階認証を利用するか否か
        $not_tfa = $user->is_tfa || $user->admin ? false : true;

        # 成功レスポンスを返す
        return response()->json([
            'valid'       => true,
            'not_tfa'     => $not_tfa ,//二段階認証を利用しない
            'err_message' => null,
        ], 200);

    }

        /** 認証メールの送信 */
        public static function sendTFAKeyMail($user)
        {
            #
            $verification_code = $user->tfa_key;

            #認証番号メールの送信
            Mail::mailer('register_auth')
            ->to( $user->email ) //宛先
            ->send(new \App\Mail\RegisterAuthMailable([
                'inputs' => compact('verification_code') , //入力変数
                'view' => 'emails.login.tfa' , //テンプレート
                'subject' => 'ログイン認証キー'.$verification_code , //件名
            ]) );
        }

    /**
     * TFAキー認証 API
     *
     * @param \Illuminate\Http\Request $request
     * @return JSON
    */
    public function api_login_tfa_key(Request $request)
    {
        # ユーザーアカウント
        $user = User::where('email', $request->email)->first();


        # 失敗回数の保存
        if( $user &&  !($user->tfa_key == $request->tfa_key) )
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
            return response()->json([
                'valid'       => false,
                'max_failures'=> true,
                'err_message'
                => 'ログインの試行回数が上限に達しました。一定時間経過後に、再度ログインをお試しください',
            ], 200);
        }


        # TFAキー認証
        if( !( $user &&  $user->tfa_key == $request->tfa_key ) )
        {
            ## 失敗レスポンス
            return response()->json([
                'valid'       => false,
                'err_message' => '認証キーが一致しません。',
            ], 200);
        }


        # 成功レスポンスを返す
        return response()->json([
            'valid'       => true,
            'err_message' => null,
        ], 200);
    }

}
