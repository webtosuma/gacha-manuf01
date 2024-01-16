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
        // dd($customer->id);

        $checkout_session = Session::create([

            'customer' => $customer->id, //顧客ID
            'customer_update'=>['address'=> 'auto'],

            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price' => $point_sail->stripe_id,
                'quantity' => 1,
            ]],
            'automatic_tax' => [ 'enabled' => true, ],

            // 'success_url' => route('point_sail.payment_post',$point_sail->stripe_id),//成功リダイレクトパス
            'success_url' => route('point_sail.comp',$point_sail->stripe_id),//成功リダイレクトパス
            'cancel_url'  => route('point_sail'),//失敗リダイレクトパス

            // 'ui_mode' => 'embedded',
            // 'return_url' => route('stripe.online.cancel'),
        ]);

        // return array('clientSecret' => $checkout_session->client_secret);
        return redirect()->to($checkout_session->url);
    }




    /**
     * 購入処理
     * @param \Illuminate\Http\Request $request
     * @param String $stripe_id
     * @return \Illuminate\Http\Response
    */
    public function payment_post(Request $request, $stripe_id)
    {
        return ;

        // $point_sail = PointSail::where('stripe_id',$stripe_id)->first();

        // # ログインユーザー取得
        // $user = Auth::user();

        // # ポイント履歴の登録
        // $point_history = new PointHistory([
        //     'user_id'   => $user->id,          //ユーザー　リレーション
        //     'value'     => $point_sail->value, //ポイント数
        //     'price'     => $point_sail->price, //販売価格(税込み)
        //     'reason_id' => 11 //入出理由ID
        // ]);
        // $point_history->save();

        // # [紹介キャンペーン]ポイント付与
        // CanpaingIntroductoryController::grant($user);

        // # [紹介キャンペーン]初回ポイント購入
        // CanpaingFirstPointSailController::grant($user);


        // // 二重送信防止
        // $request->session()->regenerateToken();


        // return redirect(route('point_sail.comp',$point_sail->stripe_id));
    }



    /**
     * ポイント購入　完了
     * @param String $stripe_id
     * @return \Illuminate\View\View
    */
    public function comp($stripe_id)
    {
        $user = Auth::user();

        # [キャンペーン]初回ポイント購入
        CanpaingFirstPointSailController::grant($user);

        # [紹介キャンペーン]ポイント付与
        CanpaingIntroductoryController::grant($user);


        $point_sail = PointSail::where('stripe_id',$stripe_id)->first();

        return $point_sail
        ? view('point_sail.comp', compact('point_sail'))
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

        } catch (\UnexpectedValueException $e) {
            // 署名が無効な場合、またはその他のエラーが発生した場合の処理
            // Log::error($e);
            return response('Webhook Signature Verification Failed', 200);
            // return response('Webhook Signature Verification Failed', 403);
        }

        // イベントタイプごとに処理を実行
        switch ($event->type) {
            case 'checkout.session.completed':
                // 支払いが成功した場合の処理
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            // 他のイベントに対する処理を追加

            default:
                // 未知のイベントに対する処理
                break;
        }

        return response('Webhook Handled', 200);
    }


    /**
     * 注文のフルフィルメントを実行するためのコード
     *
     * @param  Object $session //Stripe Checkout Session オブジェクト
     * @return Void
     */
    private function handleCheckoutSessionCompleted($session)
    {
        // 客の情報
        $user = null;
        $stripeCustomerId = $session['customer']; // Stripe Customer ID
        $users = User::all();
        foreach ($users as $user_data) {
            if( $user_data->createOrGetStripeCustomer()->id == $stripeCustomerId ){

                $user = $user_data;

            }
        }

        // 購入アイテムの情報
        $amounSubtotal = $session['amount_subtotal'];
        $point_sail    = PointSail::where('price', $amounSubtotal)->first();



        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,          //ユーザー　リレーション
            'value'     => $point_sail->value, //ポイント数
            'price'     => $point_sail->price, //販売価格(税込み)
            'reason_id' => 11 //入出理由ID
        ]);
        $point_history->save();



        // # [紹介キャンペーン]ポイント付与
        // CanpaingIntroductoryController::grant($user);

        // # [キャンペーン]初回ポイント購入
        // CanpaingFirstPointSailController::grant($user);
    }


}
