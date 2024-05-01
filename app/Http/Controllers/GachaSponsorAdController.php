<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GachaPlayCreateUserPrizeMethod
as CreateUserPrize;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\PointHistory;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use App\Models\Prize;
use App\Models\GachaRankMovie;
use App\Models\Movie;
use App\Models\Sponsor;
use App\Models\SponsorAd;
/*
| =============================================
|  ガチャ 広告ガチャPLAY コントローラー
| =============================================
*/
class GachaSponsorAdController extends Controller
{
    /**
     * PLAYガチャで遊ぶ
     *
     * @param Request $request
     * @param UserGachaHistory $user_gacha_history
     * @return \Illuminate\Http\Response
     */
    public function movie(Request $request, UserGachaHistory $user_gacha_history)
    {
        # ガチャ情報
        $gacha = $user_gacha_history->gacha;

        #　広告情報
        $sponsor_ads = $gacha->sponsor_ads;
        $sponsor_ad  = $sponsor_ads[rand( 0, $sponsor_ads->count()-1 )];

        # 広告動画再生カウント加算
        $sponsor_ad->movie_play_count ++;
        $sponsor_ad->save();

        # 取得商品
        $user_prizes = $user_gacha_history->user_prizes;

        # ランクアップの有無
        $rank_up = $request->rank_up;

        return view('gacha.pay_sponser_ad', compact('user_gacha_history', 'sponsor_ad', 'rank_up' ));
    }


    /**
     * スポンサーサイトへリダイレクト
     *
     * @param SponsorAd $sponsor_ad
     * @return \Illuminate\Http\Response
     */
    public function redirect(SponsorAd $sponsor_ad)
    {
        # スポンサーサイトへのアクセスカウント加算
        $sponsor_ad->access_count ++;
        $sponsor_ad->save();

        return redirect( $sponsor_ad->url );
    }
}
