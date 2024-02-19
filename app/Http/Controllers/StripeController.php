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
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # 販売用ポイント情報取得
        $point_sails = PointSail::where('is_published',1)//公開ずみのみ
        ->orderBy('value','asc')->get();//ポイントが低い順

        return view('point_sail.index',compact('point_sails'));
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
                'customer_balance'
            ],

            'payment_method_options' => [
                'customer_balance'=> [
                    'funding_type'=> 'bank_transfer',
                    'bank_transfer'=> [
                        'type'=> 'jp_bank_transfer',
                    ],
                ],
            ],

            'mode' => 'payment',
            'line_items' => [[
                'price' => $point_sail->stripe_id,
                'quantity' => 1,
            ]],
            'automatic_tax' => [ 'enabled' => true, ],

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

        # 表示するガチャ情報
        $before_gacha_id = $request->session()->get('before_gacha_id') ;
        $before_gacha = Gacha::find($before_gacha_id);


        $gachas = GachaController::getPublishedGachas( $category_code='all', null );

        return $point_sail
        ? view('point_sail.comp', compact('point_sail', 'before_gacha', 'gachas'))
        : \App::abort(404);
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



        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,          //ユーザー　リレーション
            'value'     => $point_sail->value, //ポイント数
            'price'     => $point_sail->price, //販売価格(税込み)
            'reason_id' => 11, //入出理由ID

            'stripe_checkout_session_id' => $session_id,//CheckoutSession
        ]);
        $point_history->save();

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
