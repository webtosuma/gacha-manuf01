<?php

namespace App\Services\Manuf;

use Stripe\Stripe;
use Stripe\Event;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\ManufPurchaseHistory;
use App\Models\ManufPurchaseItem;
use App\Models\User;
use App\Models\UserGachaHistory;

use App\Services\Gacha;
/*
| =============================================
|  Manufacturer:ガチャタイトル　購入 Stripe サービス
| =============================================
*/
class StripeService
{
    /** サービスの登録 */
    public function __construct(

        # ガチャ サービス
        private Gacha\PlayService     $gachaPlayService,//PLAY
        private Gacha\PlayDrawService $gachaDrawService,//抽選

        # 発送　 サービス
        private ShippedService $shippedService,
    ) {}


    /**
     * Checkoutセッション作成
     */
    public function createCheckoutSession(
        User    $user,
        ManufPurchaseHistory $history,//購入履歴
    ){
        Stripe::setApiKey(config('stripe.secret_key'));

        # 顧客情報
        $customer = $user->createOrGetStripeCustomer();
        $stripe = new \Stripe\StripeClient(config('stripe.secret_key'));
        $stripe->customers->update($customer->id, [
            'email' => $user->email,//メールアドレスを利用する
        ]);

        # 決済名
        $productName = "ガチャ購入";

        # 決済の種類($payment_method_types) //Manufにおいて、銀行振込・コンビニ決済は不可！
        $payment_method_types = ['card'];

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
                'history_id' => $history->id,
            ],

            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [

                        'name' => $productName,

                    ],
                    'unit_amount' => $history->total_fee,
                ],
                'quantity' => 1,
            ]],
            'automatic_tax' => [ 'enabled' => false, ],



            'success_url' => route(
                'manuf.gacha_title.purchase.comp', $history->code
            ),
            'cancel_url'  => route(
                'manuf.gacha_title.purchase.cancel', $history->code
            ),

            // 'cancel_url'  => route('manuf.gacha_title.purchase.appliy' ,[
            //     'code' => $history->code,
            //     'gacha_key' => $history->items->first()->machine->key
            // ]),

        ]);


    }



    /**
     * webhook処理
     */
    public function handleWebhook( Request $request )
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
            if (ManufPurchaseHistory::where('stripe_checkout_session_id', $session_id)->exists()) {
                return response()->json(['message' => '処理済み'], 200);
            }


            # 客の情報
            $user = User::where('stripe_id', $session['customer'])
            ->withTrashed()
            ->first();

            if (!$user) {
                return response()->json(['message' => 'ユーザーなし'], 403);
            }


            # 購入履歴の情報
            $history = ManufPurchaseHistory::withTrashed()
            ->where('id',$session['metadata']['history_id'])
            ->where('status', 'pending')//状態 :購入待ち 
            ->where('user_id',$user->id)
            ->lockForUpdate()
            ->first();

            if (!$history) {
                return response()->json(['message' => '購入履歴なし'], 403);
            }


            # 決済完了のDB情報の登録メソッド
            return self::completedMethod( $history, $user, $session_id );


        });
    }



    /**
     * 決済完了のDB情報の登録メソッド
    */
    public function completedMethod( 
        ManufPurchaseHistory $history, 
        User   $user, 
        String $session_id 
    ): ManufPurchaseHistory
    {
        # 購入アイテムの更新
        foreach ($history->items as $item) 
        {
            # ガチャの抽選( ガチャ履歴作成 )
            $gacha_history = $this->gachaPlay($item,$user);

            # 発送申請
            $shipped = $this->shippedService->appliy( 
                $gacha_history, $user, $history->address_id
            );

            # 購入アイテムの更新
            $item->update([
                'gacha_history_id' => $gacha_history->id, //ガチャ履歴           
                'shipped_id'       => $shipped->id,       //発送情報
            ]);
        }


        # 購入履歴の更新
        $history->update([
            'status'  =>'paid', //状態 :支払い済み
            'paid_at' => now(), //支払い完了日時   
            'stripe_checkout_session_id' => $session_id,
        ]);


        # サイト管理者へ送信
        /* ~ */


        return $history;
    }



    /**
     * ガチャ実行
     */
    public function gachaPlay(
        ManufPurchaseItem  $item,
        User $user
    ): UserGachaHistory
    {
        # ガチャ情報取得(他のリクエストを待機)
        $gacha = $item->machine->gacha;

        # プレイ数
        $play_count = (int) $item->count;;

        # ポイント消費
        $point_history = $this->gachaPlayService
        ->consumePoint( $user, $gacha, $play_count );

        # 履歴作成
        $gacha_history = $this->gachaPlayService
        ->createHistory( $user, $gacha, $play_count, $point_history,);

        # 抽選
        $prize_ids = $this->gachaDrawService
        ->index($gacha_history);

        # 最大ランク
        $max_rank = $this->gachaPlayService
        ->getMaxRank($prize_ids);

        # 演出動画
        $gacha_history = $this->gachaPlayService
        ->decideMovie($gacha_history, $max_rank);

        return $gacha_history;
    }


}