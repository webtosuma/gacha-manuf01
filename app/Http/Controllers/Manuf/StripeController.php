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
        ) = array_values( $this->purchaseService->getPurchaseData($request) );
        

        # 購入履歴の登録
        $history = $this->purchaseService->createhistory( 
            $user, $machine, $user_address, $play_count, $shipped_fee 
        );



        # テスト用完了メソッド *後で消す！
        $test = env('APP_DEBUG');
        if( $test )
        {
            # 決済完了のDB情報の登録メソッド
            // $session_id = 'test';
            // $this->stripeService->completedMethod( $user, $history, $session_id );
            // $request->session()->regenerateToken();// 二重送信防止

            return view('manuf.gacha.purchase.comp', [
                'history'           => $history,
                'gacha_title'       => $history->items->first()->machine->gacha_title,

                // 'play_count'        => $play_count,
                // 'machine'           => $machine,
                // 'user_address'      => $user_address,
                // 'gacha_title_price' => $gacha_title_price,
                'shipped_fee'       => $history->shipped_fee,
                'sub_total_fee'     => $history->sub_total_fee,
                'total_fee'         => $history->total_fee,
            ]);
        }
        
        # チェックアウトセッション
        $session = $this->stripeService
        ->createCheckoutSession( $user, $history );

        // return redirect()->to($session->url);
    }


}