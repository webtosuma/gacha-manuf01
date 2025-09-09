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
| Auth Yahooログイン コントローラー
|--------------------------------------------------------------------------
*/
class LoginYahooController extends Controller
{
    /**
     * Loginフォーム表示
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        return Socialite::driver('yahoo')->redirect();
    }



    /**
     * コールバック処理
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        # Yahooアカウント情報
        // $yUser = Socialite::driver('yahoo')->stateless()->user();
        $yUser = Socialite::driver('yahoo')->user();
        dd($yUser);
    }



}
