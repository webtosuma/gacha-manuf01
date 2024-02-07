<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Event;
use Stripe\Checkout\Session;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Gacha;
use App\Models\PointSail;
use App\Models\PointHistory;
use App\Http\Controllers\CanpaingIntroductoryController;//お友達紹介キャンペーン
use App\Http\Controllers\CanpaingFirstPointSailController;//初回ポイント購入キャンペーン
/*
| =============================================
|  ポイント購入 (Stripe for React) コントローラー
| =============================================
*/
class StripeReactController extends Controller
{
    /**
     * ポイント　一覧
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # 販売用ポイント情報取得
        $point_sails = PointSail::where('is_published',1)//公開ずみのみ
        ->orderBy('value','asc')->get();//ポイントが低い順

        return view('point_sail.index',compact('point_sails'));
    }


    /**
     * 購入　手続き
     * @param \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
    */
    public function payment(PointSail $point_sail)
    {
        return redirect()->route('point_sail.comp',$point_sail->stripe_id);


        return view('point_sail.stripe.payment',compact('point_sail'));
    }


    /**
     * ポイント購入　完了
     *
     * @param \Illuminate\Http\Request $request
     * @param String $stripe_id
     * @return \Illuminate\View\View
    */
    public function comp(Request $request, $stripe_id)
    {
        # 購入ポイント情報
        $point_sail = PointSail::where('stripe_id',$stripe_id)->first();

        # 支払い方法
        $source = 'クレジットカード';


        # 表示するガチャ情報
        $before_gacha_id = $request->session()->get('before_gacha_id') ;
        $before_gacha = Gacha::find($before_gacha_id);


        $gachas = GachaController::getPublishedGachas( $category_code='all', null );

        return $point_sail
        ? view('point_sail.comp', compact('point_sail','source', 'before_gacha', 'gachas'))
        : \App::abort(404);
    }


    /**
     * ポイントが不足しています
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function shortage(Request $request)
    {
        # 変数
        $gacha = Gacha::find($request->gacha_id);//ガチャ


        # ガチャIDをセッションに保存
        $request->session()->put('before_gacha_id', $gacha->id);

        return view('point_sail.shortage',compact('gacha'));
    }

}
