<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;

use App\Models\User;
use App\Models\Admin;
use App\Models\Gacha;
use App\Models\PointSail;
use App\Models\PointHistory;
use App\Models\TicketHistory;

use App\Http\Controllers\CanpaingIntroductoryController;//お友達紹介キャンペーン
use App\Http\Controllers\CanpaingFirstPointSailController;//初回ポイント購入キャンペーン

/*
| =============================================
|  ポイント購入 (fincode) コントローラー
| =============================================
*/
class FincodeController extends Controller
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

        # ランクごとのポイント還元率
        $rank_ratio = Auth::check() && Auth::user()->now_rank && env('NEW_TICKET_SISTEM',false)
        ? Auth::user()->now_rank->point_sail_ratio : 1 ;


        return view('point_sail.index',compact('point_sails', 'rank_ratio'));
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
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
    */
    public function payment(Request $request, PointSail $point_sail)
    {
        $API_KEY  = 'Bearer '.config('fincode.secret_key','');
        $BASE_URL = config('fincode.base_url','');
        $endpoint = "/v1/sessions";
        $user = Auth::user();


        // # 顧客情報の処理
        // if( ! self::getCustomer() )
        // {
        //     /* 顧客情報の登録 */
        //     self::createCustomer();

        // }else
        // {
        //     /* 顧客情報の更新 */
        //     self::updateCustomer();
        // }



        $DATA = [
            //成功リダイレクトパス
            'success_url' => route('point_sail.comp_post', [
                'stripe_id' => $point_sail->stripe_id,
                'client_field_1'=>(String) $user->id,
                'client_field_2'=>(String) $point_sail->id,
                'client_field_3'=>(String) config('app.key'),
            ] ),

            //失敗リダイレクトパス
            'cancel_url'  => route('point_sail.post'),


            'transaction' => [

                # 支払いの種類
                "pay_type" => ["Card"],// "Card", "Applepay", "Konbini", "Paypay",

                # 支払い金額
                'amount'    => (String) $point_sail->price,

                # 任意の文字列
                'client_field_1'=>(String) $user->id,
                'client_field_2'=>(String) $point_sail->id
            ],


            "card" => [
                "job_code"   => "CAPTURE",//売上確定
                "tds_type"   => "2",//3Dセキュア利用種別 2-3Dセキュア2.0を利用
                "tds2_type"  => "2",//3Dセキュア2.0非対応時の挙動設定 2-3エラー
                "tds2_email" => $user->email,
            ],
        ];

        $client = new Client();

        try {
            $response = $client->post($BASE_URL . $endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Authorization' => $API_KEY,
                ],
                'json' => $DATA,
            ]);

            $body     = json_decode($response->getBody(), true);
            $link_url = $body['link_url'];


            return redirect()->to($link_url);

        } catch (\Exception $e) {

            // テスト中の時

            if(  config('app.debug') )
            {
                return $e->getMessage();
            }
            else
            {
                // 本番環境のとき
                return \App::abort(404);
            }



            return response()->json([
                'error' => 'Error creating payment URL', 'message' => $e->getMessage()
            ], 500);
        }
    }





    /**
     * ポイント購入　完了
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @param String $stripe_id
     * @return \Illuminate\View\View
    */
    public function comp(Request $request, $stripe_id )
    {
        # 購入ポイント情報
        $point_sail = PointSail::where('stripe_id',$stripe_id)->first();

        $user =  Auth::user();

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
            'point_sail', 'rank_ratio', 'before_gacha', 'gachas','category_code','user',
        )) : \App::abort(404);
    }
        /* 決済完了後のPOST受け取り */
        public function comp_post(Request $request, $stripe_id)
        {
            $bool = $this->handleCheckoutSessionCompleted($request);

            return $bool
            ? redirect()->route('point_sail.comp', $stripe_id )
            : \App::abort(404);
        }


    /**
     * 決済完了webhook
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function webhook(Request $request)
    {
        # サイト管理者へ送信
        $email   = 't.sakai@tosuma.ltd';
        Mail::to( $email ) //宛先
        ->send(new \App\Mail\SendHtmlMailMailable([
            'inputs'  => ['json'=>json_encode($request->all())], //入力変数
            'view'    => 'emails.payment.webhook' , //テンプレート
            'subject' => 'ポイント購入webhookの送信' , //件名
        ]) );

        return response(['message'=>'test'], 200);



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
    private function handleCheckoutSessionCompleted($request)
    {
        // dd($request->all());
        // dd($request->client_field_3 == config('app.key'));


        # 客の情報
        $user = User::find($request->client_field_1);

        # 購入アイテムの情報
        $point_sail = PointSail::find($request->client_field_2);

        # APPキーチェック
        $app_key_bool = $request->client_field_3 == config('app.key');


        if( !( $user && $point_sail && $app_key_bool ) ){ return false; }


        # ランクごとのポイント還元率
        $rank_ratio = $user->now_rank && env('NEW_TICKET_SISTEM',false)
        ? $user->now_rank->point_sail_ratio : 1 ;


        # ポイント履歴の登録
        $point_history = new PointHistory([
            'user_id'   => $user->id,          //ユーザー　リレーション
            'value'     => floor( $point_sail->value * $rank_ratio ),//ポイント数
            'price'     => $point_sail->price, //販売価格(税込み)
            'reason_id' => 11, //入出理由ID

            'stripe_checkout_session_id' => 'fincode',//CheckoutSession
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



        return true;
    }

}
