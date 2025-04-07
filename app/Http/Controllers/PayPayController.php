<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPay\OpenPaymentAPI\Client;
use PayPay\OpenPaymentAPI\Models\CreatePaymentPayload;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;
use PayPay\OpenPaymentAPI\Models\AccountLinkPayload;
use PayPay\OpenPaymentAPI\Models\OrderItem;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Gacha;
use App\Models\PointSail;
use App\Models\PointHistory;
use App\Models\TicketHistory;
use App\Models\PayPayOrder;

use App\Http\Controllers\CanpaingIntroductoryController;//お友達紹介キャンペーン
use App\Http\Controllers\CanpaingFirstPointSailController;//初回ポイント購入キャンペーン

use App\Services\PayPayService;
/*
| =============================================
|  ポイント購入 (PayPay) コントローラー
| =============================================
*/
class PayPayController extends Controller
{
    /**
     * 購入　手続き
     *
     * @param Request $request
     * @param \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
    */
    public function payment(Request $request, PointSail $point_sail)
    {

        DB::beginTransaction();
        try {


            # PayPayクライアントの初期化
            $isProduction = config('paypay.environment');
            $client = new Client([
                'API_KEY'     => config('paypay.api_key'),
                'API_SECRET'  => config('paypay.api_secret'),
                'MERCHANT_ID' => config('paypay.merchant_id'),
            ],$isProduction);


            # 購入商品情報
            $orderName = $point_sail->value.'pt購入';  // 商品名等
            $price     = $point_sail->price;          // 決済金額


            $items = (new OrderItem())->setName($orderName)
            ->setQuantity(1)
            ->setUnitPrice(['amount' => $price, 'currency' => 'JPY']);


            # 決済完了後の案内ページを表示する。(paypay APIに支払い情報を送信し、paypayの決済ページにリダイレクトさせる。)
            // $paypayMerchantPaymentId = 'mpid_' . rand() . time();           // PayPay決済成功時のWebhookに含めるユニークとなる決済ID
            $paypayMerchantPaymentId = PayPayOrder::CreatePaypayMerchantPaymentId();// PayPay決済成功時のWebhookに含めるユニークとなる決済ID
            $redirectUrl = route('point_sail.comp',$point_sail->stripe_id); // PayPay決済成功後のリダイレクト先URL

            $CQPayload = new CreateQrCodePayload();
            $CQPayload->setOrderItems($items);
            $CQPayload->setMerchantPaymentId($paypayMerchantPaymentId);
            $CQPayload->setCodeType('ORDER_QR');
            $CQPayload->setAmount(['amount' => $price, 'currency' => 'JPY']);
            $CQPayload->setIsAuthorization(false);
            $CQPayload->setUserAgent($_SERVER['HTTP_USER_AGENT']);
            $CQPayload->setRedirectType('WEB_LINK');
            $CQPayload->setRedirectUrl($redirectUrl);

            $QRCodeResponse = $client->code->createQRCode($CQPayload);
            if ($QRCodeResponse['resultInfo']['code'] !== 'SUCCESS') {
                throw new \Exception('決済用QRコードが生成できませんでした');
            }

            # PayPay注文情報の登録
            $paypay_order = new PayPayOrder([
                'user_id'                    => Auth::user()->id,         //ユーザーID
                'point_sail_id'              => $point_sail->id,          //販売ポイントID
                'paypay_merchant_payment_id' => $paypayMerchantPaymentId, //PayPay決済ID
            ]);
            $paypay_order->save();


            DB::commit();
            return redirect()->to($QRCodeResponse['data']['url']);       // PayPayの決済画面に遷移

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
        }

        return \App::abort(404);

    }


    /**
     * 決済webhook
     *
     * @param Request $request
     * @param \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
    */
    public function webhook(Request $request)
    {
        // return response()->json( [], 200 );

        DB::beginTransaction();
        try {
            $state = $request->state;
            $paypayMerchantPaymentId = $request->merchant_order_id;

            if ($state === 'FAILED') {
                throw new \Exception('オーダーステータス: ' . $state);
            }


            # PayPay注文情報の呼び出し
            $paypay_order = PayPayOrder::where('paypay_merchant_payment_id',$paypayMerchantPaymentId)
            ->where('point_history_id',null)
            ->first();
            if ( empty($paypay_order) ) { throw new \Exception('PayPay注文情報が存在しません'); }


            # 顧客の情報
            $user = $paypay_order->user;
            if ( empty($user) ) { throw new \Exception('顧客情報が存在しません'); }

            # 販売ポイント情報
            $point_sail = $paypay_order->point_sail;
            if ( empty($point_sail) ) { throw new \Exception('販売ポイント情報が存在しません'); }


            # ランクごとのポイント還元率
            $rank_ratio = $user->now_rank && env('NEW_TICKET_SISTEM',false)
            ? $user->now_rank->point_sail_ratio : 1 ;


            # ポイント履歴の登録
            $point_history = new PointHistory([
                'user_id'   => $user->id,          //ユーザー　リレーション
                'value'     => floor( $point_sail->value * $rank_ratio ),//ポイント数
                'price'     => $point_sail->price, //販売価格(税込み)
                'reason_id' => 11, //入出理由ID
            ]);
            $point_history->save();


            #チケットの付与
            if( $point_sail->ticket > 0 )
            {
                $ticket_history = new TicketHistory([
                    'user_id'   => $user->id,
                    'value'     => $point_sail->ticket,
                    'reason_id' => 16, //ポイント購入時プレゼント
                ]);
                $ticket_history->save();
            }


            # [紹介キャンペーン]ポイント付与
            CanpaingIntroductoryController::grant($user);

            # [キャンペーン]初回ポイント購入
            CanpaingFirstPointSailController::grant($user);


            # ポイント購入完了メールの送信
            $request->user       = $user;
            $request->point_sail = $point_sail;
            $request->email      = !config('app.debug') ? env('PAYMENT_COMP_EMAIL') : 't.sakai@tosuma.ltd';//ローカルではメール送信しない
            SendMailController::PaymentComp( $request );

            DB::commit();


            return response()->json( compact(
                'paypay_order','user','point_sail','point_history'
            ), 200 );

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
        }
    }
}
