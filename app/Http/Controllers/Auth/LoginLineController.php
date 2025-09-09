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
| Auth LINEログイン コントローラー
|--------------------------------------------------------------------------
*/
class LoginLineController extends Controller
{
    /**
     * Loginフォーム表示
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // 既にログイン済みならトップへ
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // return Socialite::driver('line')->redirect();

        $state = Str::random(32);
        $nonce = Str::random(32);

        $uri = "https://access.line.me/oauth2/v2.1/authorize?";
        $response_type = "response_type=code";
        $client_id     = "&client_id=" . config('services.line.client_id');
        // dd($client_id);
        $redirect_uri  = "&redirect_uri=" . config('services.line.redirect');
        // dd($redirect_uri);
        $state_uri     = "&state=" . $state;
        $scope         = "&scope=openid%20profile";
        $prompt        = "&prompt=consent";
        $nonce_uri     = "&nonce=". $nonce;

        $uri = $uri . $response_type . $client_id . $redirect_uri . $state_uri . $scope . $prompt . $nonce_uri;
        // dd($uri);

        return redirect($uri);
    }




    /**
     * コールバック処理
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {

        $tokenResponse = $this->getAccessToken($request);
        $accessToken   = $tokenResponse->access_token;
        $idToken       = $tokenResponse->id_token;

        $profile = $this->getProfile($accessToken);
        // $email   = $this->getEmail($idToken);//取得できない

        # 適当なメールアドレスの生成
        $email = Str::random(16).'@example.com';


        # ユーザー情報あるか確認
        $user = User::where('line_id', $profile->userId)->first();

        # あったらログイン
        if ( $user ) {

            # ログイン
            Auth::login($user);

            # マイページTOPへリダイレクト
            return
            redirect()->route('gacha_category')
            ->with('alert-primary','ログインしました。');


        # なければ登録してからログイン
        } else {
            $user = new User([
                'name'     => $profile->displayName,
                'email'    => $email,
                'password' => Hash::make( Str::random(32) ),
                'line_id'  => $profile->userId,
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




    /**
     * アクセストークン取得
     */
    public function getAccessToken($req) {
        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        $post_data = [
            'grant_type' => 'authorization_code',
            'code' => $req['code'],
            'redirect_uri'  => config('services.line.redirect'),
            'client_id'     => config('services.line.client_id'),
            'client_secret' => config('services.line.client_secret'),
        ];
        // dd($post_data);
        $url = 'https://api.line.me/oauth2/v2.1/token';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res); // ← 配列ではなくオブジェクトを返す
    }

    /**
     * プロフィール取得
     */
    public function getProfile($at) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $at]);
        curl_setopt($curl, CURLOPT_URL, 'https://api.line.me/v2/profile');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res);
    }

    /**
     * メールアドレス取得
     */
    public function getEmail($idToken) {
        $url = 'https://api.line.me/oauth2/v2.1/verify';
        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        $post_data = [
            'id_token'  => $idToken,
            'client_id' => config('services.line.client_id'),
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
        $res = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($res, true);

        return $json['email'] ?? null;
    }
}
