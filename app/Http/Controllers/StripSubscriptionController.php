<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Event;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Gacha;
use App\Models\PointSail;
use App\Models\PointHistory;
use App\Models\TicketHistory;
use App\Models\UserSubscription;


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
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # 顧客情報
        $user = Auth::user();

        # サブスクプラン
        $subscriptions = PointSail::where('is_subscription',true)//サブスクのみ
        ->where('is_published',true)//公開中のみ
        ->orderByDesc('value','asc')//ポイントが高い順
        ->get();
        foreach ($subscriptions as $subscription) {
            $subscription->r_checkout = route('point_sail.subscription.checkout',$subscription->stripe_id);
        }


        /* test */
        $subscription = $subscriptions[0];

        // dd(
        //     $subscription
        // );



        return view('point_sail.subscription.index',compact('subscriptions'));
    }



    /**
     * checkout
     *
     * @param $subscription_id//商品ID
     * @return \Illuminate\Http\Response
    */
    public function checkout($subscription_id)
    {
        Stripe::setApiKey( config('stripe.secret_key') );

        # 顧客情報
        $user = Auth::user();
        $customer = $user->createOrGetStripeCustomer();


        # 商品情報
        $price_id = $subscription_id;


        // タイムスタンプを取得(日付サイクル:毎月1日)
        $nextMonthFirstDay = now()->copy()->addMonths(1)->startOfMonth();
        $timestamp = $nextMonthFirstDay->timestamp;


        $checkout_session = Session::create([

            'customer' => $customer->id, //顧客ID
            'customer_update'=>['address'=> 'auto'],
            'payment_method_types' => [ 'card',],
            // 'subscription_data' => [ 'billing_cycle_anchor' =>  $timestamp],//日割り計算処理

            'line_items' => [[
                'price' => $price_id,
                'quantity' => 1,
            ]],
            'shipping_address_collection' => [
                'allowed_countries' => ['JP'], // 配送可能な国を指定
            ],

            'automatic_tax' => [ 'enabled' => false, ],


            'mode' => 'subscription',


            'payment_method_options' => [
                'card' => [
                    'request_three_d_secure' => 'any' // 3Dセキュアを強制
                ]
            ],
            'success_url' => route('point_sail.subscription.comp',$subscription_id),//成功リダイレクトパス
            'cancel_url'  => route('point_sail.subscription'),//失敗リダイレクトパス
        ]);


        return redirect()->to($checkout_session->url);
    }



    /**
     * 契約　完了
     *
     * @param \Illuminate\Http\Request $request
     * @param String $stripe_id
     * @return \Illuminate\View\View
    */
    public function comp(Request $request, $stripe_id)
    {
        # サズスクプランの取得($subscription)
        $subscription = PointSail::where('stripe_id',$stripe_id)->first();
        if( ! $subscription ){ return \App::abort(404); }

        # 表示するガチャ情報
        $before_gacha_id = $request->session()->get('before_gacha_id') ;
        $before_gacha = Gacha::find($before_gacha_id);

        # カテゴリーコード
        $category_code = $before_gacha ? $before_gacha->category->code_name : 'all';

        # おすすめガチャ
        $gachas = GachaController::getPublishedGachas( $category_code, null );


        return  view('point_sail.subscription.comp', compact(
            'subscription', 'before_gacha', 'gachas','category_code'
        ));
    }





    /**
     * 決済完了webhook
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function webhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $sigHeader = $request->header('Stripe-Signature'); // シークレットキーを使ってStripeの署名を検証する
        $endpointSecret = config('stripe.subscription_endpoint_secret'); // Stripeダッシュボードで生成されたシークレットキー

        try {

            $event   = Event::constructFrom($payload, $sigHeader, $endpointSecret);
            $session = $event->data->object;


            // イベントタイプごとに処理を実行
            switch ($event->type) {

                // case 'customer.subscription.created':
                // case 'customer.subscription.updated':

                ## サブスクが更新されました
                case 'invoice.payment_succeeded':

                    /* サブスクの[支払い完了]フルフィルメントを実行 */
                    return $this->handleInvoicePaymentSucceeded($session);
                    break;


                ## サブスクが削除されました
                case 'customer.subscription.deleted':

                    /* サブスクの[契約キャンセル]フルフィルメントを実行 */
                    return $this->handleCoustomerSubscriptionDeleted($session);
                    break;


                default:
                    // 未知のイベントに対する処理
                    return response()->json([], 200);
                    break;
            }


        } catch (\UnexpectedValueException $e) {
            // 署名が無効な場合、またはその他のエラーが発生した場合の処理
            Log::error($e);
            return response()->json(['Error parsing payload: ' => $e->getMessage()], 403);

        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // 署名が無効な場合、またはその他のエラーが発生した場合の処理
            Log::error($e);
            return response()->json(['Error verifying webhook signature: ' => $e->getMessage()], 403);
        }
    }


    /**
     * サブスクの[契約更新]フルフィルメントを実行するためのコード
     *
     * @param  Object $session //Stripe Checkout Session オブジェクト
     * @return Json
     */
    // private function handleCoustomerSubscriptionUpdate($request, $session)
    private function handleInvoicePaymentSucceeded($session)
    {
        # CheckoutSessionが処理済みの時は、スキップ

            $session_id = $session['id'];
            $column     = 'stripe_checkout_session_id';
            $check_day  = now()->copy()->subHour(23);

            $old_point_history =
            PointHistory::where($column,$session_id)->where('created_at','>',$check_day)->first();

            $response = ['message' => '契約内容は処理済みです。'];
            if( $old_point_history ){ return response()->json( $response, 200 );}


        # 顧客の情報( $user )

            $user = User::where('stripe_id', $session['customer'])
            ->withTrashed()//退会者を含む
            ->first();

            $response = ['message' => '一致するユーザー情報がありません。'];
            if( !$user ){ return response()->json( $response, 403 ); }


        # サブスクプランの情報( $subscription )

            $sub_stripe_id = $session->lines->data[0]->plan['id'];
            $subscription = PointSail::where('stripe_id',$sub_stripe_id)
            ->withTrashed()//削除を含む
            ->first();

            $response = ['message' => 'サブスク情報がありません。'];
            if( !$subscription ){ return response()->json( $response, 403 ); }


        # 契約中のサブスク情報($user_subscription) ＊初回は新規登録

            $user_subscription = UserSubscription::where('user_id',$user->id)
            ->where('subscription_id',$subscription->id)
            ->first();

            /*更新か否か*/
            $is_update = isset($user_subscription);

            /*新規登録*/
            if( !$user_subscription ){
                $user_subscription = new UserSubscription([
                    'user_id'         => $user->id,         //ユーザー　リレーション
                    'subscription_id' => $subscription->id ,//サブスクプランとのリレーション（PointSail）
                ]);
                $user_subscription->save();
            }


        # ポイント履歴の登録

            # 入出理由ID
            $reason_id = !$is_update
            ? $subscription->sub_reason_ids['create'] : $subscription->sub_reason_ids['update'];

            $point_history = new PointHistory([
                'user_id'   => $user->id,
                'value'     => $subscription->value,
                'price'     => $subscription->price, //販売価格(税込み)
                'reason_id' => $reason_id, //入出理由ID

                'stripe_checkout_session_id' => $session_id,//CheckoutSessionID
            ]);
            $point_history->save();



        # 完了メール送信(サイト管理者)

            $inputs  = compact('user','subscription');
            $subject = 'ID:'.$user->id.' '.$user->name.'様の、'.$subscription->sub_label.'プランの支払いが完了しました';
            $email   = !config('app.debug') ? env('PAYMENT_COMP_EMAIL') : 't.sakai@tosuma.ltd';//ローカルではメール送信しない

            Mail::to( $email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs'  => $inputs, //入力変数
                'view'    => 'emails.payment_subscription_update.admin' , //テンプレート
                'subject' => $subject , //件名
            ]) );


        # 200レスポンスを返す
        return response()->json( compact(
            'user','subscription','user_subscription','point_history'
        ), 200);
    }



    /**
     * サブスクの[契約キャンセル]フルフィルメントを実行するためのコード
     *
     * @param  Object $session //Stripe Checkout Session オブジェクト
     * @return Json
     */
    private function handleCoustomerSubscriptionDeleted($session)
    {
        // return response()->json(['手動'], 200);

        # 顧客の情報( $user )

            $user = User::where('stripe_id', $session->customer )
            ->withTrashed()//退会者を含む
            ->first();

            $response = ['message' => '一致するユーザー情報がありません。', 'session'=>$session];
            if( !$user ){ return response()->json( $response, 403 ); }


        # サブスクプランの情報( $subscription )

            $sub_stripe_id = $session->items->data[0]->plan['id'];//deleteの時は、'items'を指定
            $subscription = PointSail::where('stripe_id',$sub_stripe_id)
            ->withTrashed()//削除を含む
            ->first();

            $response = ['message' => 'サブスク情報がありません。'];
            if( !$subscription ){ return response()->json( $response, 403 ); }


        # 契約中のサブスク情報($user_subscription)の削除

            $user_subscription = UserSubscription::where('user_id',$user->id)
            ->where('subscription_id',$subscription->id)
            ->first();

            $message =  '指定のユーザーとサブスクの契約はありません';
            if( !$user_subscription ){ return response()->json( compact( 'message','user','subscription', ), 200); }

            /*契約中サブスクを削除*/
            $user_subscription->delete();


        # ポイント履歴の登録
            $reason_id = $subscription->sub_reason_ids['delete'];//入出理由
            $session_id = $session['id'];

            $point_history = new PointHistory([
                'user_id'   => $user->id,
                'reason_id' => $reason_id, //入出理由ID
                'stripe_checkout_session_id' => $session_id,//CheckoutSessionID
            ]);
            $point_history->save();



        # 完了メール送信(サイト管理者)

            $inputs  = compact('user','subscription');
            $subject = 'ID:'.$user->id.' '.$user->name.'様の、'.$subscription->sub_label.'プランのキャンセル申請がありました';
            $email   = !config('app.debug') ? env('PAYMENT_COMP_EMAIL') : 't.sakai@tosuma.ltd';//ローカルではメール送信しない

            Mail::to( $email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs'  => $inputs, //入力変数
                'view'    => 'emails.payment_subscription_delete.admin' , //テンプレート
                'subject' => $subject , //件名
            ]) );


        # 200レスポンスを返す
        return response()->json( compact('user','subscription','point_history'), 200);
    }


}
