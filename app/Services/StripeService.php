<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Event;
use Stripe\Checkout\Session;
use Stripe\StripeClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\PointSail;
use App\Models\PointHistory;
use App\Models\TicketHistory;
use App\Http\Controllers\CanpaingIntroductoryController;
use App\Http\Controllers\CanpaingFirstPointSailController;
/*
| =============================================
|  ポイント購入 (Stripe) サービス
| =============================================
*/
class StripeService
{
    /**
     * Checkoutセッション作成
     */
    public function createCheckoutSession($user, $point_sail)
    {
        Stripe::setApiKey(config('stripe.secret_key'));

        # 顧客情報
        $customer = $user->createOrGetStripeCustomer();
        $stripe = new \Stripe\StripeClient(config('stripe.secret_key'));
        $stripe->customers->update($customer->id, [
            'email' => $user->email,//メールアドレスを利用する
        ]);

        # ランクごとのポイント還元率
        $rank_ratio = $user->now_rank && config('u_rank_ticket.user_rank', false)
        ? $user->now_rank->point_sail_ratio : 1;

        # 購入ポイント
        $point = floor($point_sail->value * $rank_ratio);

        # 決済名
        $productName = number_format($point) . 'pt購入';


        # 決済の種類($payment_method_types)
        $payment_method_types = [];
        $payment_types_settings =  config('stripe.payment_method_types');
        $payment_keys = [ 'card','konbini','customer_balance' ];
        foreach ( $payment_keys as $key) {
            if( (bool) $payment_types_settings[$key] ){ $payment_method_types[] = $key; }
        }

        # チェックアウトセッション
        return Session::create([

            'customer' => $customer->id,
            'customer_update'=>['address'=> 'auto'],

            'payment_method_types' => $payment_method_types,

            'payment_method_options' => [
                'customer_balance'=> [
                    'funding_type'=> 'bank_transfer',
                    'bank_transfer'=> [
                        'type'=> 'jp_bank_transfer',
                    ],
                ],
                'card' => [ //3Dセキュア
                    'request_three_d_secure' => 'any' ,
                ],
            ],

            'mode' => 'payment',

            'metadata' => [
                'point_sail_id' => $point_sail->id,
            ],

            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [

                        'name' => $productName,

                    ],
                    'unit_amount' => $point_sail->price,
                ],
                'quantity' => 1,
            ]],
            'automatic_tax' => [ 'enabled' => false, ],

            'success_url' => route('point_sail.comp', $point_sail->stripe_id),
            'cancel_url' => route('point_sail'),
        ]);


    }



    /**
     * webhook処理
     */
    public function handleWebhook($request)
    {
        $payload = json_decode($request->getContent(), true);
        $sigHeader = $request->header('Stripe-Signature'); // シークレットキーを使ってStripeの署名を検証する
        $endpointSecret = config('stripe.subscription_endpoint_secret'); // Stripeダッシュボードで生成されたシークレットキー

        try {

            $event = Event::constructFrom($payload, $sigHeader, $endpointSecret);
            $session = $event->data->object;


            # EC商品の決済のとき
            if( isset( $session['metadata']['store_history_id'] ) )
            {
                $store_stripe_controller = new \App\Http\Controllers\Store\StripeController;
                return $store_stripe_controller->webhook($event,$session);
            }
            

            #  該当するイベントタイプに処理を実行
            switch ($event->type) {

                ## クレジット・ウォレットで支払いが成功した場合の処理
                case 'checkout.session.completed':
                ## 銀行振込などの非同期型決済での、発送業務等のシステム連携処理
                case 'checkout.session.async_payment_succeeded':

                    return $this->handleCompleted($session);

                ## 未知のイベントに対する処理
                default:

                    return response()->json(['message'=>'未知のイベントが処理されました。'], 200);
                    
            }

        } catch (\Exception $e) {

            Log::error($e);
            return response(['error' => $e->getMessage()], 403);

        }


    }



    /**
     * 決済完了処理
     */
    public function handleCompleted($session)
    {
        return DB::transaction(function () use ($session) {


            # Stripe側で決済が完了済(paid)でなければ、スキップ
            $paid = isset($session->payment_status) && $session->payment_status === 'paid';
            $response = ['message' => '決済が未完了です。'];
            if( !$paid ){ return response()->json( $response, 200 );}


            # CheckoutSessionが処理済みの時は、スキップ
            $session_id = $session['id'];
            if (PointHistory::where('stripe_checkout_session_id', $session_id)->exists()) {
                return response()->json(['message' => '処理済み'], 200);
            }


            # 客の情報
            $user = User::where('stripe_id', $session['customer'])
            ->withTrashed()
            ->first();

            if (!$user) {
                return response()->json(['message' => 'ユーザーなし'], 403);
            }


            # 購入アイテムの情報
            $point_sail = PointSail::withTrashed()
            ->find($session['metadata']['point_sail_id']);

            if (!$point_sail) {
                return response()->json(['message' => '商品なし'], 403);
            }


            # 決済完了のDB情報の登録メソッド
            return self::completedMethod( $point_sail, $user, $session_id );


        });
    }



    /**
     * 決済完了のDB情報の登録メソッド
    */
    public function completedMethod( $point_sail, $user, $session_id )
    {

        # ランクごとのポイント還元率
        $rank_ratio = $user->now_rank && config('u_rank_ticket.user_rank',false)
        ? $user->now_rank->point_sail_ratio : 1 ;


        # ポイント履歴の登録
        $point_history = PointHistory::create([
            'user_id'   => $user->id,
            'value'     => $point_sail->value * $rank_ratio,
            'price'     => $point_sail->price,
            'reason_id' => 11,
            'stripe_checkout_session_id' => $session_id,
        ]);


        #チケットの付与
        if( $point_sail->ticket > 0 )
        {
            TicketHistory::create([
                'user_id'   => $user->id,
                'value'     => $point_sail->ticket,
                'reason_id' => 16,
            ]);
        }


        # [紹介キャンペーン]ポイント付与
        CanpaingIntroductoryController::grant($user);

        # [キャンペーン]初回ポイント購入
        CanpaingFirstPointSailController::grant($user);


        # サイト管理者へ送信
        $subject = 'ID:'.$user->id.' '.$user->name.'様が、'.$point_sail->value.'pt購入しました';
        $email   = !config('app.debug') ? env('PAYMENT_COMP_EMAIL') : 't.sakai@tosuma.ltd';//ローカルではメール送信しない
        $inputs  = compact('user','point_sail','email');
        
        Mail::to( $email ) //宛先
        ->send(new \App\Mail\SendHtmlMailMailable([
            'inputs'  => $inputs, //入力変数
            'view'    => 'emails.payment_comp.admin' , //テンプレート
            'subject' => $subject , //件名
        ]) );


        return $point_history;
    }


    /**
     * カスタマーポータルセッションの作成
     */
    public function createCustomerPortalSession($user)
    {
        Stripe::setApiKey( config('stripe.secret_key') );

        # 顧客情報
        $customer = $user->createOrGetStripeCustomer();

        # Stripeクライアントを初期化
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        # カスタマーポータルセッションを作成
        return $stripe->billingPortal->sessions->create([
            'customer' => $customer->id,
            'return_url' => route('settings'), // ポータルを終了した後にリダイレクトするURL
        ]);
    }



}