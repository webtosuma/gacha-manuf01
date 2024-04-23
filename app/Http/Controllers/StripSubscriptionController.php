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
    private function Subscriptions()
    {
                // $price_id = 'price_1P1fYnKoJdkajOL0nIegnltI';//月時テスト
                $price_id = 'price_1P3AfaKoJdkajOL0gGm7CMDU';//日時テスト

        return [
            // 月時テスト
            'price_1P1fYnKoJdkajOL0nIegnltI' => [
                'price'        => 3000,
                'label'        => 'テスト月額 3,000円(税込)プラン',
                'point_value'  => 3500,
                'ticket_value' => 40,
                'create_history_id'=> 201,
                'update_history_id'=> 202,
                'delete_history_id'=> 203,
            ],

            //日時テスト
            'price_1P3AfaKoJdkajOL0gGm7CMDU' => [
                'price'        => 100,
                'label'        => 'テスト日額 100円(税込)プラン',
                'point_value'  => 350,
                'ticket_value' => 4,
                'create_history_id'=> 211,
                'update_history_id'=> 212,
                'delete_history_id'=> 213,
            ],

        ];
    }



    /**
     * サブスク　一覧
     * @return \Illuminate\View\View
    */
    public function index()
    {
        $subscriptions = self::Subscriptions();
        // dd($subscriptions);


        return view('point_sail.subscription.index',compact('subscriptions'));
    }



    /**
     * 購入　手続き
     * @param $subscription_id//商品ID
     * @return \Illuminate\Http\Response
    */
    public function payment($subscription_id)
    {
        Stripe::setApiKey( config('stripe.secret_key') );

        # 顧客情報
        $user = Auth::user();
        $customer = $user->createOrGetStripeCustomer();


        # 商品情報
        $price_id = $subscription_id;
        // $price_id = 'price_1P1fYnKoJdkajOL0nIegnltI';//月時テスト
        // $price_id = 'price_1P3AfaKoJdkajOL0gGm7CMDU';//日時テスト

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

                    /* サブスクの[契約更新]フルフィルメントを実行 */
                    return $this->handleCoustomerSubscriptionUpdate($request, $session);
                    break;


                ## サブスクが更新されました
                case 'customer.subscription.updated':

                    /* サブスクの[契約更新]フルフィルメントを実行 */
                    $session = $event->data->object;
                    return $this->handleCoustomerSubscriptionUpdate($request, $session);
                    break;


                ## サブスクが削除されました
                case 'customer.subscription.deleted':

                    /* サブスクの[契約キャンセル]フルフィルメントを実行 */
                    $session = $event->data->object;
                    return $this->handleCoustomerSubscriptionDeleted($request, $session);
                    break;


                default:
                    // 未知のイベントに対する処理
                    return response([], 200);
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
     * サブスクの[契約更新]フルフィルメントを実行するためのコード
     *
     * @param  Object $session //Stripe Checkout Session オブジェクト
     * @return Json
     */
    private function handleCoustomerSubscriptionUpdate($request, $session)
    {
        # CheckoutSessionが処理済みの時は、スキップ
        $session_id = $session['id'];
        $column = 'stripe_checkout_session_id';
        $previous_point_history = PointHistory::where($column,$session_id)->first();
        if( $previous_point_history ){
            return response(['message' => '契約内容は処理済みです。'], 200);
        }


        # 客の情報
        $user = User::where('stripe_id', $session['customer'])->first();
        if( !$user ){
            return response(['message' => '一致するユーザー情報がありません。'], 403);
        }


        # サブスクプランの情報
        $subscription_id = $session->items->data[0]->plan['id'];
        $subscription    = self::Subscriptions()[$subscription_id];
        if( !$subscription ){
            return response(['message' => 'サブスク情報がアプリケーション側で登録されていません。'], 403);
        }


        # 新規契約のとき=>UserにサブスクIDを保存
        $is_update = isset( $user->subscription_id );
        if(!$user->subscription_id){
            $user->update(['subscription_id'=>$subscription_id]);
        }


        # 入出理由ID
        $reason_id = !$is_update ? $subscription['create_history_id'] : $subscription['update_history_id'];


        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,
            'value'     => $subscription['point_value'],
            'price'     => $subscription['price'], //販売価格(税込み)
            'reason_id' => $reason_id, //入出理由ID

            'stripe_checkout_session_id' => $session_id,//CheckoutSessionID
        ]);
        $point_history->save();


        #チケットの付与
        $ticket_history = new TicketHistory([
            'user_id'   => $user->id,
            'value'     => $subscription['ticket_value'],
            'reason_id' => $reason_id, //入出理由ID
        ]);
        $ticket_history->save();



        # 200レスポンスを返す
        return response( compact('user','subscription','point_history','ticket_history'), 200);
    }



    /**
     * サブスクの[契約キャンセル]フルフィルメントを実行するためのコード
     *
     * @param  Object $session //Stripe Checkout Session オブジェクト
     * @return Json
     */
    private function handleCoustomerSubscriptionDeleted($request, $session)
    {
        # CheckoutSessionが処理済みの時は、スキップ
        $session_id = $session['id'];
        $column = 'stripe_checkout_session_id';
        $previous_point_history = PointHistory::where($column,$session_id)->first();
        if( $previous_point_history ){
            return response(['message' => '契約内容は処理済みです。'], 200);
        }


        # 客の情報
        $user = User::where('stripe_id', $session['customer'])->first();
        if( !$user ){
            return response(['message' => '一致するユーザー情報がありません。'], 403);
        }


        # サブスクプランの情報
        $subscription_id = $session->items->data[0]->plan['id'];
        $subscription    = self::Subscriptions()[$subscription_id];
        if( !$subscription ){
            return response(['message' => 'サブスク情報がアプリケーション側で登録されていません。'], 403);
        }


        # 新規契約のとき=>UserにサブスクIDを保存
        $is_update = isset( $user->subscription_id );
        if(!$user->subscription_id){
            return response(['message' => 'ユーザーのサブスク契約情報が登録されていません。'], 403);
        }


        # 入出理由ID
        $reason_id = $subscription['delete_history_id'];



        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,
            'value'     => 0,
            'price'     => 0, //販売価格(税込み)
            'reason_id' => $reason_id, //入出理由ID

            'stripe_checkout_session_id' => $session_id,//CheckoutSessionID
        ]);
        $point_history->save();




        # 200レスポンスを返す
        return response( compact('user','subscription','point_history',), 200);
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


        return ['message'=>'OK'];


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


    // private function handleCustomerSubscriptionCreated
}
