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
use App\Models\TicketHistory;

use App\Http\Controllers\CanpaingIntroductoryController;//お友達紹介キャンペーン
use App\Http\Controllers\CanpaingFirstPointSailController;//初回ポイント購入キャンペーン

/*
| =============================================
|  ポイント購入 (Stripe) サブスク コントローラー
| =============================================
*/
class StripSubscriptionController extends Controller
{
    /**
     * サブスク　一覧
     * @return \Illuminate\View\View
    */
    public function index()
    {
        $subscriptions = [];


        return view('point_sail.subscription.index',compact('subscriptions'));
    }



    /**
     * 購入　手続き
     * @return \Illuminate\Http\Response
    */
    public function payment()
    {
        Stripe::setApiKey( config('stripe.secret_key') );

        # 顧客情報
        $user = Auth::user();
        $customer = $user->createOrGetStripeCustomer();


        # 商品情報
        $price_id = 'price_1P1fYnKoJdkajOL0nIegnltI';//月時テスト
        $price_id = 'price_1P3AfaKoJdkajOL0gGm7CMDU';//日時テスト

        $checkout_session = Session::create([

            'customer' => $customer->id, //顧客ID
            'customer_update'=>['address'=> 'auto'],
            'payment_method_types' => [ 'card',],
            // 'billing_cycle_anchor_config' => ['day_of_month' => 1],//請求サイクルの起点

            'line_items' => [[
                'price' => $price_id,
                'quantity' => 1,
            ]],

            'mode' => 'subscription',
            'success_url' => route('point_sail.subscription'),//成功リダイレクトパス
            'cancel_url'  => route('point_sail.subscription'),//失敗リダイレクトパス
            // 'subscription_data' => [ 'billing_cycle_anchor' =>  15],
        ]);


        return redirect()->to($checkout_session->url);
    }




    /**
     * 決済完了webhook
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function webhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        // シークレットキーを使ってStripeの署名を検証する
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('stripe.endpoint_secret'); // Stripeダッシュボードで生成されたシークレットキー
        $event = null;

        try {

            $event = Event::constructFrom($payload, $sigHeader, $endpointSecret);


            // イベントタイプごとに処理を実行
            switch ($event->type) {

                ## サブスクが作成されました
                case 'customer.subscription.created':
                    $session = $event->data->object;

                    $responses = $this->handlePaymentIntentSucceeded($request,$session);

                    return response($responses, 200);
                    break;


                ## サブスクが削除されました
                case 'customer.subscription.deleted':
                    $session = $event->data->object;

                    $responses = $this->handlePaymentIntentSucceeded($request,$session);

                    return response($responses, 200);
                    break;


                ## クレジット・ウォレットで支払いが成功した場合の処理
                case 'payment_intent.succeeded':
                    $session = $event->data->object;

                    $responses = $this->handlePaymentIntentSucceeded($request,$session);

                    return response($responses, 200);
                    break;


                ## サブスクが更新されました
                case 'customer.subscription.updated':
                    $session = $event->data->object;

                    $responses = $this->handlePaymentIntentSucceeded($request,$session);

                    return response([], 200);
                    break;


                ## 支払いが失敗しました。
                case 'invoice.payment_failed':
                    $session = $event->data->object;

                    $responses = $this->handlePaymentIntentSucceeded($request,$session);

                    return response($responses, 200);
                    break;


                default:
                    // 未知のイベントに対する処理
                    break;
            }


        } catch (\UnexpectedValueException $e) {
            // 署名が無効な場合、またはその他のエラーが発生した場合の処理
            Log::error($e);
            return response(['Error parsing payload: ' => $e->getMessage()], 403);

        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // 署名が無効な場合、またはその他のエラーが発生した場合の処理
            Log::error($e);
            return response(['Error verifying webhook signature: ' => $e->getMessage()], 403);
        }
    }


    /**
     * サブスクのフルフィルメントを実行するためのコード
     *
     * @param  Object $session //Stripe Checkout Session オブジェクト
     * @return Void
     */
    private function handlePaymentIntentSucceeded($request, $session)
    {
        # Stripe側で決済が完了済(paid)でなければ、スキップ
        $paid = isset($session->payment_status) && $session->payment_status === 'paid';
        if( !$paid ){ return null; }


        # CheckoutSessionが処理済みの時は、スキップ
        $session_id = $session['id'];
        $column = 'stripe_checkout_session_id';
        $previous_point_history = PointHistory::where($column,$session_id)->first();
        if( $previous_point_history ){ return null; }


        return [];


        # 客の情報
        $user = User::where('stripe_id', $session['customer'])->first();

        # 購入アイテムの情報
        $amounSubtotal = $session['amount_subtotal'];
        $point_sail    = PointSail::where('price', $amounSubtotal)->first();

        # ランクごとのポイント還元率
        $rank_ratio = $user->now_rank && env('NEW_TICKET_SISTEM',false)
        ? $user->now_rank->point_sail_ratio : 1 ;


        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,          //ユーザー　リレーション
            'value'     => $point_sail->value * $rank_ratio,//ポイント数
            'price'     => $point_sail->price, //販売価格(税込み)
            'reason_id' => 11, //入出理由ID

            'stripe_checkout_session_id' => $session_id,//CheckoutSession
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


        # ポイント購入完了メールの送信
        $request->user       = $user;
        $request->point_sail = $point_sail;
        $request->email      = !config('app.debug') ? env('PAYMENT_COMP_EMAIL') : 't.sakai@tosuma.ltd';//ローカルではメール送信しない
        SendMailController::PaymentComp( $request );



        return $point_history;
    }
}
