<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CanpaingIntroductoryController;//お友達紹介キャンペーン
use App\Http\Controllers\SendMailController;
/*
|--------------------------------------------------------------------------
| Auth Xログイン コントローラー
|--------------------------------------------------------------------------
*/
class LoginXController extends Controller
{
    /**
     * Loginフォーム表示
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        return Socialite::driver('twitter')->redirect();
    }



    /**
     * コールバック処理
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        # Xアカウント情報
        $xUser = Socialite::driver('twitter')->stateless()->user();

        # ユーザー情報あるか確認
        $twitter_id = '@'.$xUser->nickname;
        $user_xid   = User::where('twitter_id', $twitter_id)->first();
        $user_email = User::where('email',    $xUser->email)->first();

        # あったらログイン
        if ( $user_xid || $user_email ) {

            $user = $user_xid ?? $user_email ;

            # ログイン
            Auth::login($user);

            #twitter_idの更新
            $user->twitter_id = $twitter_id;
            $user->save();

            # マイページTOPへリダイレクト
            return
            redirect()->route('gacha_category')
            ->with('alert-primary','ログインしました。');


        # なければ登録してからログイン
        } else {
            $user = new User([
                'twitter_id' => $twitter_id,   //X(旧twitter)ID
                'name'       => $xUser->name,
                'email'      => $xUser->email,
                'password'   => \Hash::make(uniqid()),
            ]);
            $user->save();

            # [紹介キャンペーン]ユーザー紹介履歴の登録
            CanpaingIntroductoryController::createRecord( $request, $user );

            # 求職者・管理者へメール送信
            SendMailController::UserAuthRegiste( $user );

            # ログイン
            Auth::login($user);

            # リダイレクト
            return redirect()->route('register.comp')
            ->with(['alert_register'=>'会員登録が完了しました。']);
        }
    }



}
