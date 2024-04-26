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
        # 顧客情報
        $user = Auth::user();

        # サブスクプラン
        $subscriptions = self::Subscriptions();
        foreach($subscriptions as $subscription_id => $subscription)
        {
            // 解約予約中か否か($subscription['destory'])
            $query = PointHistory::query();

                $query->where('user_id', $user->id);

                $query->where('reason_id',$subscription['delete_history_id'] );

                //一ヶ月以内のデータ
                $check_day  = now()->copy()->subMonth();
                $query->where('created_at','>',$check_day);


            $point_history = $query->orderByDesc('created_at')->first();

            $subscriptions[$subscription_id]['destory']    = isset($point_history);
            $subscriptions[$subscription_id]['destory_at'] = $point_history? $point_history->created_at->addMonth() :null;//解約予定日
        }
        // $session_id = "sub_1P8ahoKoJdkajOL0zzmYxEW1";
        // $column     = 'stripe_checkout_session_id';
        // $check_day  = now()->copy()->subHour(23);
        // // dd($check_day->format('Ymd His'));
        // $previous_point_history =
        // PointHistory::where($column,$session_id)->where('created_at','>',$check_day)->first();
        // dd($previous_point_history);


        return view('point_sail.subscription.index',compact('subscriptions'));
    }



    /**
     * サブスクプラン契約　手続き
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


            'mode' => 'subscription',
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
        $user = Auth::user();

        # サズスクプランの取得($subscription)
        $subscriptions = self::Subscriptions();
        if( !array_key_exists($stripe_id, $subscriptions) ){ return \App::abort(404); }
        $subscription = $subscriptions[$stripe_id];
        // dd($subscription['label']);

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
     * サブスクプラン解約
     *
     * @param \Illuminate\Http\Request $request
     * @param String $stripe_id
     * @return \Illuminate\View\View
    */
    public function destroy(Request $request, $stripe_id)
    {
        # 顧客情報
        $user = Auth::user();

        # サズスクプランの取得($subscription)
        $subscriptions = self::Subscriptions();
        if( !array_key_exists($stripe_id, $subscriptions) ){ return \App::abort(404); }
        $subscription = $subscriptions[$stripe_id];


        # ポイント履歴よりセッションIDの取得
        $query = PointHistory::query();

            $query->where('user_id', $user->id);

            $query->whereIn('reason_id', [
                $subscription['create_history_id'], $subscription['update_history_id']
            ]);

            //一ヶ月以内のデータ
            $check_day  = now()->copy()->subMonth();
            $query->where('created_at','>',$check_day);


        $point_history = $query->orderByDesc('created_at')->first();

        $session_id = $point_history->stripe_checkout_session_id;


        # 解約処理
        $stripe = new \Stripe\StripeClient(config('stripe.secret_key'));
        $stripe->subscriptions->update($session_id, ['cancel_at_period_end' => true]);


        # ポイント履歴の登録
        $reason_id = $subscription['delete_history_id'];// 入出理由ID
        $point_history = new PointHistory([
            'user_id'   => $user->id,
            'value'     => 0,
            'price'     => 0, //販売価格(税込み)
            'reason_id' => $reason_id, //入出理由ID

            'stripe_checkout_session_id' => $session_id,//CheckoutSessionID
        ]);
        $point_history->save();



        $message = $subscription['label'].'を解約しました。';
        return redirect()->route('point_sail.subscription')
        ->with(['alert-danger'=>$message]);
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
        $column     = 'stripe_checkout_session_id';
        $check_day  = now()->copy()->subHour(23);

        $previous_point_history =
        PointHistory::where($column,$session_id)->where('created_at','>',$check_day)->first();

        // $session_id = $session['id'];
        // $column     = 'stripe_checkout_session_id';
        // $previous_point_history = PointHistory::where($column,$session_id)->first();
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


        # UserkaraサブスクIDを削除
        $is_update = isset( $user->subscription_id );
        if(!$user->subscription_id){
            return response(['message' => 'ユーザーのサブスク契約情報が登録されていません。'], 403);
        }
        $user->update(['subscription_id'=>null]);//削除


        // # 入出理由ID
        // $reason_id = $subscription['delete_history_id'];



        // # ポイント履歴の登録
        // $point_history = new PointHistory([
        //     'user_id'   => $user->id,
        //     'value'     => 0,
        //     'price'     => 0, //販売価格(税込み)
        //     'reason_id' => $reason_id, //入出理由ID

        //     'stripe_checkout_session_id' => $session_id,//CheckoutSessionID
        // ]);
        // $point_history->save();




        # 200レスポンスを返す
        return response( compact('user','subscription','point_history',), 200);
    }


}
