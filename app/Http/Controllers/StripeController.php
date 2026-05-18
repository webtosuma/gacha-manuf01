<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\Gacha;
use App\Models\PointSail;
use App\Services\StripeService;
/*
| =============================================
|  ポイント購入 (Stripe) コントローラー
| =============================================
*/

class StripeController extends Controller
{
    /** サービスの登録 */
    private $stripeService;
    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }



    /**
     * 購入　手続き
     * @param \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
    */
    public function payment(PointSail $point_sail)
    {
        # 顧客情報
        $user = auth()->user();

        # テスト用完了メソッド *後で消す！
        $test = env('APP_DEBUG');
        if( $test )
        {
            # 決済完了のDB情報の登録メソッド
            $session_id = 'stripe_checkout_session_id';
            $this->stripeService->completedMethod( $point_sail, $user, $session_id );

            return redirect()->route('point_sail.comp',$point_sail->stripe_id);
        }
        
        # チェックアウトセッション
        $session = $this->stripeService
        ->createCheckoutSession($user, $point_sail);

        return redirect()->to($session->url);
    }




    /**
     * webhook処理
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function webhook(Request $request)
    {
        return $this->stripeService->handleWebhook($request);
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
        $user = Auth::user();

        $point_sail = PointSail::where('stripe_id',$stripe_id)->first();

        # ランクごとのポイント還元率
        $rank_ratio = Auth::check() && Auth::user()->now_rank && config('u_rank_ticket.user_rank',false)
        ? Auth::user()->now_rank->point_sail_ratio : 1 ;

        # 表示するガチャ情報
        $before_gacha_id = $request->session()->get('before_gacha_id') ;
        $before_gacha = Gacha::find($before_gacha_id);

        # カテゴリーコード
        $category_code = $before_gacha ? $before_gacha->category->code_name : 'all';

        # おすすめガチャ
        $gachas = GachaController::getPublishedGachas( $category_code, null );


        return $point_sail
        ? view('point_sail.comp', compact(
            // 'point_sail', 'rank_ratio', 'before_gacha', 'gachas'
            'point_sail', 'rank_ratio', 'before_gacha', 'gachas','category_code'
        )) : abort(404);
    }




    /**
     * カスタマーポータル
     *
     * @return \Illuminate\Http\Response
    */
    public function customer_portal()
    {        
        # 顧客情報
        $user = Auth::user();

        # カスタマーポータルセッションの作成
        $session = $this->stripeService->createCustomerPortalSession($user);

        # カスタマーポータルへのリダイレクト
        return redirect( $session->url );
    }


}