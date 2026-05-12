<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManufPurchaseHistory;
use App\Services\Manuf\StripeService;
use App\Services\Manuf\PurchaseService;
use App\Services\Manuf\GachaTitleService;
use App\Services\Manuf\ShippedService;
/*
| =============================================
|  Manufacturer:ガチャタイトル　購入 Stripe コントローラー
| =============================================
*/
class StripeController extends Controller
{
    # サービスの登録
    public function __construct(
        protected StripeService     $stripeService,
        protected PurchaseService   $purchaseService,
        protected GachaTitleService $gachaTitleService,       
        protected ShippedService    $shippedService, // 発送サービス
    ) {}


    /**
     * webhook処理
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function webhook(Request $request)
    {
        return $this->stripeService->handleWebhook($request);
    }



    /**
     * 購入[決済チェックアウト]
     * @return \Illuminate\Http\Response
    */
    public function checkout(
        Request $request,
    ){
        # 購入パラメーター情報取得
        list(
            $user,
            $play_count,
            $machine,
            $gacha_title,
            $user_address,//
            $gacha_title_price,
            $shipped_fee,
            $sub_total_fee,
            $total_fee,
        ) = $this->purchaseService->getPurchaseData($request);


        # 購入履歴の登録
        $history = $this->purchaseService->createhistory( 
            $user, $machine, $user_address, $play_count, $shipped_fee 
        );



        # テスト用完了メソッド *後で消す！
        $test = env('APP_DEBUG');
        if( $test )
        {
            // # 決済完了のDB情報の登録メソッド
            // $session_id = 'stripe_checkout_session_id';
            // $this->stripeService->completedMethod( $point_sail, $user, $session_id );

            // return redirect()->route('point_sail.comp',$point_sail->stripe_id);
        }
        
        # チェックアウトセッション
        $session = $this->stripeService
        ->createCheckoutSession($user, $history);

        return redirect()->to($session->url);
    }


}