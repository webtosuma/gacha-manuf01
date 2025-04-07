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
use App\Models\User;
use App\Models\Gacha;
use App\Models\PointSail;
use App\Models\PointHistory;
use App\Models\TicketHistory;

use App\Http\Controllers\CanpaingIntroductoryController;//お友達紹介キャンペーン
use App\Http\Controllers\CanpaingFirstPointSailController;//初回ポイント購入キャンペーン

/*
| =============================================
|  ポイント購入 (Stripe) コントローラー
| =============================================
*/
class StripeController extends Controller
{
    /**
     * ポイント　一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function index(Request $request)
    {
        # 支払いタイプテキスト
        $payment_type = $request->payment_type;

        # 販売用ポイント情報取得
        $point_sails = PointSail::where('is_published',1)//公開ずみのみ
        ->orderBy('value','asc')->get();//ポイントが低い順

        # 支払いタイプ別支払いページURL
        switch ($payment_type) {
            case 'PayPay':
                foreach ($point_sails as $point_sail) {
                    $point_sail->r_payment = route('point_sail.paypay.payment', $point_sail);
                }
                break;

            default:
                foreach ($point_sails as $point_sail) {
                    $point_sail->r_payment = route('point_sail.payment', $point_sail);
                }
                break;
        }

        # ランクごとのポイント還元率
        $rank_ratio = Auth::check() && Auth::user()->now_rank && env('NEW_TICKET_SISTEM',false)
        ? Auth::user()->now_rank->point_sail_ratio : 1 ;



        return view('point_sail.index',compact('point_sails', 'rank_ratio','payment_type' ));
    }



    /**
     * カスタマーポータル
     *
     * @return \Illuminate\Http\Response
    */
    public function customer_portal()
    {
        Stripe::setApiKey( config('stripe.secret_key') );

        # 顧客情報
        $user = Auth::user();
        $customer = $user->createOrGetStripeCustomer();


        # Stripeクライアントを初期化
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        # カスタマーポータルセッションを作成
        $session = $stripe->billingPortal->sessions->create([
            'customer' => $customer->id,
            'return_url' => route('settings'), // ポータルを終了した後にリダイレクトするURL
        ]);

        # カスタマーポータルへのリダイレクト
        return redirect($session->url);
    }



    /**
     * ポイントが不足しています
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function shortage(Request $request)
    {
        # 変数
        $gacha = Gacha::find($request->gacha_id);//ガチャ
        $play_count = $request->play_count;

        # ガチャIDをセッションに保存
        $request->session()->put('before_gacha_id', $gacha->id);

        ## 表示できるガチャ一覧
        $category_code = $gacha->category->code_name;
        $gachas = GachaController::getPublishedGachas( $category_code, null );


        return view('point_sail.shortage',compact('gacha','play_count','gachas','category_code'));
    }



    /**
     * 購入　手続き
     * @param \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
    */
    public function payment(PointSail $point_sail)
    {
        Stripe::setApiKey( config('stripe.secret_key') );

        # 顧客情報
        $user = Auth::user();
        $customer = $user->createOrGetStripeCustomer();

        $checkout_session = Session::create([

            'customer' => $customer->id, //顧客ID
            'customer_update'=>['address'=> 'auto'],

            'payment_method_types' => [
                'card',
                // 'konbini',
                // 'customer_balance'
            ],

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
            'line_items' => [[
                'price' => $point_sail->stripe_id,
                'quantity' => 1,
            ]],
            'shipping_address_collection' => [
                'allowed_countries' => ['JP'], // 配送可能な国を指定
            ],
            'automatic_tax' => [ 'enabled' => false, ],

            'success_url' => route('point_sail.comp',$point_sail->stripe_id),//成功リダイレクトパス
            'cancel_url'  => route('point_sail'),//失敗リダイレクトパス
        ]);

        return redirect()->to($checkout_session->url);
    }



    /**
     * ポイント購入　完了
     *
     * @param \Illuminate\Http\Request $request
     * @param String $stripe_id
     * @return \Illuminate\View\View
    */
    public function comp(Request $request, $stripe_id)
    {
        $user = Auth::user();

        // # [キャンペーン]初回ポイント購入
        // CanpaingFirstPointSailController::grant($user);

        // # [紹介キャンペーン]ポイント付与
        // CanpaingIntroductoryController::grant($user);


        $point_sail = PointSail::where('stripe_id',$stripe_id)->first();
        // dd($point_sail);

        # ランクごとのポイント還元率
        $rank_ratio = Auth::check() && Auth::user()->now_rank && env('NEW_TICKET_SISTEM',false)
        ? Auth::user()->now_rank->point_sail_ratio : 1 ;


        # 表示するガチャ情報
        $before_gacha_id = $request->session()->get('before_gacha_id') ;
        $before_gacha = Gacha::find($before_gacha_id);


        # カテゴリーコード
        $category_code = $before_gacha ? $before_gacha->category->code_name : 'all';

        # おすすめガチャ
        $gachas = GachaController::getPublishedGachas( $category_code, null );
        // return  view('point_sail.subscription.comp', compact('subscription', 'before_gacha', 'gachas','category_code'));

        return $point_sail
        ? view('point_sail.comp', compact(
            // 'point_sail', 'rank_ratio', 'before_gacha', 'gachas'
            'point_sail', 'rank_ratio', 'before_gacha', 'gachas','category_code'
        )) : \App::abort(404);
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

                ## クレジット・ウォレットで支払いが成功した場合の処理
                case 'checkout.session.completed':
                    $session = $event->data->object;

                    $point_history = $this->handleCheckoutSessionCompleted($request,$session);

                    return response(compact('point_history'), 200);
                    break;


                ## 銀行振込などの非同期型決済での、発送業務等のシステム連携処理
                case 'checkout.session.async_payment_succeeded':
                    $session = $event->data->object;

                    $point_history = $this->handleCheckoutSessionCompleted($request,$session);

                    return response(compact('point_history'), 200);
                    break;


                ##
                // case 'payment_intent.succeeded':
                //     $session = $event->data->object;
                //     if (isset($session->payment_status) && $session->payment_status === 'paid') {
                //         $point_history = $this->handleCheckoutSessionCompleted($session);
                //     }

                //     return response(compact('point_history'), 200);
                //     break;



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
     * 注文のフルフィルメントを実行するためのコード
     *
     * @param  Object $session //Stripe Checkout Session オブジェクト
     * @return Void
     */
    private function handleCheckoutSessionCompleted($request, $session)
    {
        # Stripe側で決済が完了済(paid)でなければ、スキップ
        $paid = isset($session->payment_status) && $session->payment_status === 'paid';
        if( !$paid ){ return null; }


        # CheckoutSessionが処理済みの時は、スキップ
        $session_id = $session['id'];
        $column = 'stripe_checkout_session_id';
        $previous_point_history = PointHistory::where($column,$session_id)->first();
        if( $previous_point_history ){ return null; }


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
            'value'     => floor( $point_sail->value * $rank_ratio ),//ポイント数
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


        # [紹介キャンペーン]ポイント付与
        CanpaingIntroductoryController::grant($user);

        # [キャンペーン]初回ポイント購入
        CanpaingFirstPointSailController::grant($user);


        # ポイント購入完了メールの送信
        $request->user       = $user;
        $request->point_sail = $point_sail;
        $request->email      = !config('app.debug') ? env('PAYMENT_COMP_EMAIL') : 't.sakai@tosuma.ltd';//ローカルではメール送信しない
        SendMailController::PaymentComp( $request );



        return $point_history;
    }


}
