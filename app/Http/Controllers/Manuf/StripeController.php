<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Manuf\StripeService;
use App\Services\Manuf\PurchaseService;
use App\Services\Manuf\ValidationService;
/*
| =============================================
|  Manufacturer:ガチャタイトル　購入 Stripe コントローラー
| =============================================
*/
class StripeController extends Controller
{
    # サービス・コントローラーの登録
    public function __construct(
        protected StripeService      $stripeService,
        protected PurchaseService    $purchaseService,   // 購入サービス
        protected ValidationService  $validationService, // バリデーションサービス
        protected PurchaseController $purchaseController, //テスト用
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
        # 購入パラメーター情報取得(購入サービス)
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
        

        # 購入[確認] バリデーションチェック
        if( $message = $this->validationService->purchaseConfirm(
            $request, $machine, $admin=false
        ) ) { abort(404); }


        # 購入履歴の登録(購入サービス)
        $history = $this->purchaseService->createhistory( 
            $user, $machine, $user_address, $play_count, $shipped_fee 
        );


        # テスト用完了メソッド 
        $test = env('APP_DEBUG');
        if( $test )
        {
            return DB::transaction(function () use ( $history, $user, $request ) {

                # 決済完了のDB情報の登録メソッド
                $session_id = 'test';

                # 決済完了後の処理
                $this->stripeService->completedMethod( $history, $user, $session_id );
                $request->session()->regenerateToken();// 二重送信防止

                # ページ表示
                return $this->purchaseController->comp($history->code);
            });
        }
        

        # チェックアウトセッション
        $session = $this->stripeService
        ->createCheckoutSession( $user, $history );

        return redirect()->to($session->url);
    }



}