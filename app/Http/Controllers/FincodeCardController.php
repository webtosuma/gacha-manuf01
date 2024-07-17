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
|  ポイント購入 (fincode クレジットカード) コントローラー
| =============================================
*/
class FincodeCardController extends Controller
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


        return view('point_sail.fincode_card.index', compact('point_sails', 'rank_ratio'));
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
     * 購入手続き 支払いクレジットカード選択
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
    */
    public function payment(Request $request, PointSail $point_sail)
    {
        # 顧客情報の処理
        $cosutomer = !self::getCustomer() /* 顧客情報の取得 */
        ? self::createCustomer()        /* 顧客情報の登録 */
        : self::updateCustomer()        /* 顧客情報の更新 */
        ;

        # 顧客カード情報
        $card_list = $cosutomer['card_list'];

        # ランクごとのポイント還元率
        $rank_ratio = Auth::check() && Auth::user()->now_rank && env('NEW_TICKET_SISTEM',false)
        ? Auth::user()->now_rank->point_sail_ratio : 1 ;

        $reduction_point = $rank_ratio ? floor( $point_sail->value*($rank_ratio-1) ) : $point_sail->value ; /* 還元ポイント */

        return view('point_sail.fincode_card.select_card', compact('point_sail', 'card_list', 'reduction_point' ) );
    }




    /**
     * 購入処理
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @param PointSail $point_sail
     * @return \Illuminate\View\View
    */
    public function process(Request $request, PointSail $point_sail )
    {
        # リクエストの変数
        $card_id   = $request['card_id'];

        # fincode顧客情報
        $cosutomer   = self::getCustomer();
        $customer_id = $cosutomer['id'];
        $card_list   = $cosutomer['card_list'];

        $holder_name = '';
        foreach ($card_list as $card) {
            $holder_name = $card['id']==$card_id ? $card['holder_name'] : $holder_name ;
        }

        /* 決済の登録メソッド */
        $crate_data = self::createPayent( $point_sail, $holder_name );
        if( !$crate_data ){ return '決済に失敗しました。'; }
        $order_id  = $crate_data['id'];
        $access_id = $crate_data['access_id'];

        /* 決済の実行 */
        $bool = self::executionPayment( $order_id, $access_id, $card_id, $customer_id, $holder_name );
        if( !$bool ){  $request->session()->regenerateToken(); return '決済に失敗しました。'; }

        /* 3Dセキュア　承認実行 */
        $bool = self::secure2($access_id);
        if( !$bool ){  $request->session()->regenerateToken(); return '決済に失敗しました。'; }
        // dd($bool);

        /* 承認後決済の実行 */
        $bool = self::securePayment($order_id, $access_id);
        if( !$bool ){  $request->session()->regenerateToken(); return '決済に失敗しました。'; }

        # 客の情報
        $user = Auth::user();

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


        # 二重投稿防止
        $request->session()->regenerateToken();


        #　完了ページリダイレクト
        $stripe_id = $point_sail->stripe_id;
        return redirect()->route('point_sail.comp', $stripe_id );
    }



    /**
     * ポイント購入　完了
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @param String $stripe_id
     * @return \Illuminate\View\View
    */
    // public function comp(Request $request, PointHistory $point_history)
    public function comp(Request $request, $stripe_id )
    {
        # 購入ポイント情報
        $point_sail = PointSail::where('stripe_id',$stripe_id)->first();
        if( !$point_sail ){ return \App::abort(404); }

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

        return view('point_sail.comp', compact(
            'point_sail',
            'rank_ratio', 'before_gacha', 'gachas','category_code','user',
        ));

    }




    /**
     * クレジットカード登録(リダイレクト)
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PointSail $point_sail
     * @return \Illuminate\Http\Response
    */
    public function create_card(Request $request, PointSail $point_sail)
    {
        $API_KEY  = 'Bearer '.config('fincode.secret_key','');
        $BASE_URL = config('fincode.base_url','');
        $endpoint = "/v1/card_sessions";
        $user = Auth::user();
        $customerId = $user->fincode_id ?? 'hogehoge';//からのとき、適当なID

        $DATA = [
            'customer_id' => $customerId,
            "tds_type"   => "2",//3Dセキュア利用種別 2-3Dセキュア2.0を利用
            "tds2_type"  => "2",//3Dセキュア2.0非対応時の挙動設定 2-3エラー
            "tds2_email" => $user->email,

            //成功リダイレクトパス
            // 'success_url' => route('point_sail.payment', $point_sail ),
            'success_url' => route('point_sail.payment.post', $point_sail ),

            //失敗リダイレクトパス
            // 'cancel_url'  => route('point_sail.post'),
            'cancel_url'  => route('point_sail.payment.post', $point_sail ),

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
         * 顧客取得メソッド
         */
        public function getCustomer()
        {
            $user = Auth::user();
            $customerId = $user->fincode_id ?? 'hogehoge';//からのとき、適当なID
            // $customerId = 'c_ibc2eZGsRyGyG2eQPKDmLw';//削除


            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/customers/{$customerId}";


            $client = new Client([
                'base_uri' => $BASE_URL,
                'headers' => [
                    'Authorization' => $API_KEY,
                ],
            ]);

            try {
                $response = $client->get($endpoint);
                $responseBody = json_decode($response->getBody(), true);

                // APIからのデータを処理
                $json = response()->json($responseBody);
                $data = $json->original;

                // カード情報
                $data['card_list'] = self::getCreditCard($customerId);

                return $data;


            } catch (\Exception $e) {
                // エラー処理
                return null;

                // return response()->json([
                //     'error' => 'API request failed',
                //     'message' => $e->getMessage(),
                // ], 500);
            }
        }




        /**
         * 顧客登録メソッド
        */
        public function createCustomer()
        {
            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/customers";
            $user = Auth::user();


            $client = new Client([
                'base_uri' => $BASE_URL,
                'headers' => [
                    'Authorization' => $API_KEY,
                    'Content-Type'  => 'application/json',
                ],
            ]);

            $data = [
                'name'  => $user->name,
                'email' => $user->email
            ];

            try {
                $response = $client->post($endpoint, [
                    'json' => $data,
                ]);

                $responseBody = json_decode($response->getBody(), true);

                // APIからのデータを処理
                $json = response()->json($responseBody);
                $data = $json->original;
                $data['card_list'] = [];// カード情報

                //顧客IDを保存
                $user->update([ 'fincode_id'=>$data['id'] ]);

                return $data;


            } catch (\Exception $e) {
                // エラー処理
                return null;

                // return response()->json([
                //     'error' => 'API request failed',
                //     'message' => $e->getMessage(),
                // ], 500);
            }
        }



        /**
         * 顧客更新メソッド
        */
        public function updateCustomer()
        {
            $user = Auth::user();
            $customerId = $user->fincode_id;
            // $customerId = 'c_ibc2eZGsRyGyG2eQPKDmLw';//削除

            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/customers/{$customerId}";

            $client = new Client([
                'base_uri' => $BASE_URL,
                'headers' => [
                    'Authorization' => $API_KEY,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = [
                'name'  => $user->name,
                'email' => $user->email
            ];

            try {
                $response = $client->request('PUT', $endpoint, [
                    'json' => $data,
                ]);

                $responseBody = json_decode($response->getBody(), true);

                // APIからのデータを処理
                $json = response()->json($responseBody);
                $data = $json->original;
                // カード情報
                $data['card_list'] = self::getCreditCard($customerId);
                return $data;

            } catch (\Exception $e) {
                // エラー処理
                return response()->json([
                    'error' => 'API request failed',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }



        /**
         * 顧客のクレジットカード情報取得メソッド
         */
        public function getCreditCard($customerId)
        {
            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/customers/{$customerId}/cards";


            $client = new Client([
                'base_uri' => $BASE_URL,
                'headers' => [
                    'Authorization' => $API_KEY,
                ],
            ]);

            try {
                $response = $client->get($endpoint);
                $responseBody = json_decode($response->getBody(), true);

                // APIからのデータを処理
                $json = response()->json($responseBody);
                return $json->original['list'];


            } catch (\Exception $e) {
                // エラー処理
                return null;
            }
        }



        /**
         * 決済の登録メソッド
        */
        public function createPayent( $point_sail, $holder_name )
        {
            // dd($card_id);


            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/payments";
            $user = Auth::user();


            $client = new Client([
                'base_uri' => $BASE_URL,
                'headers' => [
                    'Authorization' => $API_KEY,
                    'Content-Type'  => 'application/json',
                ],
            ]);

            $data = [
                "pay_type"    => "Card",
                "amount"      => (String) $point_sail->price,
                "job_code"    => "CAPTURE",//売上確定

                "tds_type"   => "2",//3Dセキュア利用種別 2-3Dセキュア2.0を利用
                "tds2_type"  => "2",//3Dセキュア2.0非対応時の挙動設定 2-3エラー
                "tds2_email" => $user->email,
                "holder_name" => $holder_name,//3Dセキュア必須
            ];


            try {
                $response = $client->post($endpoint, [
                    'json' => $data,
                ]);

                $responseBody = json_decode($response->getBody(), true);

                // APIからのデータを処理
                $json = response()->json($responseBody);
                return $json->original;


            } catch (\Exception $e) {
                // エラー処理
                return null;

                // return response()->json([
                //     'error' => 'API request failed',
                //     'message' => $e->getMessage(),
                // ], 500);

                dd(
                    response()->json([
                        'error' => 'API request failed',
                        'message' => $e->getMessage(),
                    ], 500)
                );
            }
        }



        /**
         * 決済の実行メソッド
         */
        public function executionPayment( $order_id, $access_id, $card_id, $customerId, $holder_name )
        {
            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/payments/{$order_id}";
            $user = Auth::user();


            $client = new Client([
                'base_uri' => $BASE_URL,
                'headers' => [
                    'Authorization' => $API_KEY,
                    'Content-Type'  => 'application/json',
                ],
            ]);


            $data = [
                "pay_type"    => "Card",//決済種別
                "method"      => "1",   //支払方法:一括払い
                "tds2_ret_url"=> route('point_sail.failure'),//加盟店戻りURL

                "access_id"   => $access_id,
                "customer_id" => $customerId,
                "card_id"     => $card_id,
                "holder_name" => $holder_name,//3Dセキュア必須

                "job_code"   => "CAPTURE",//売上確定
                "tds_type"   => "2",//3Dセキュア利用種別 2-3Dセキュア2.0を利用
                "tds2_type"  => "2",//3Dセキュア2.0非対応時の挙動設定 2-3エラー
                "tds2_email" => $user->email,
            ];


            # 決済APIの実行
            try {
                $response = $client->put($endpoint, [ 'json' => $data, ]);

                $responseBody = json_decode($response->getBody(), true);

                // APIからのデータを処理
                $json = response()->json($responseBody);
                return $json->original;

            } catch (\Exception $e) {
                // エラー処理
                return null;

                // return response()->json([
                //     'error' => 'API request failed',
                //     'message' => $e->getMessage(),
                // ], 500);


                // dd(
                //     response()->json([
                //         'error' => 'API request failed',
                //         'message' => $e->getMessage(),
                //     ], 500)
                // );

            }
        }




        /**
         * 3Dセキュア　承認実行
        */
        public function secure2($access_id)
        {
            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/secure2/{$access_id}";
            $user = Auth::user();


            $client = new Client([
                'base_uri' => $BASE_URL,
                'headers' => [
                    'Authorization' => $API_KEY,
                    'Content-Type'  => 'application/json',
                ],
            ]);

            $data = [
                "param" => "<Value you received in tds2_ret_url>"
            ];


            # 決済APIの実行
            try {
                $response = $client->put($endpoint, [ 'json' => $data, ]);

                $responseBody = json_decode($response->getBody(), true);

                // APIからのデータを処理
                $json = response()->json($responseBody);
                return $json;

            } catch (\Exception $e) {
                // エラー処理
                return null;

                dd(
                    response()->json([
                        'error' => 'API request failed',
                        'message' => $e->getMessage(),
                    ], 500)
                );
            }

        }



        /**
         *
        */

        /**
         * 承認後決済の実行
        */
        public function securePayment($order_id, $access_id)
        {
            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/payments/{$order_id}/secure";
            $user = Auth::user();


            $client = new Client([
                'base_uri' => $BASE_URL,
                'headers' => [
                    'Authorization' => $API_KEY,
                    'Content-Type'  => 'application/json',
                ],
            ]);

            $data = [
                "pay_type"    => "Card",//決済種別
                "access_id"   => $access_id,
            ];


            # 決済APIの実行
            try {
                $response = $client->put($endpoint, [ 'json' => $data, ]);

                $responseBody = json_decode($response->getBody(), true);

                // APIからのデータを処理
                $json = response()->json($responseBody);
                return $json->original;

            } catch (\Exception $e) {
                // エラー処理
                return null;

                dd(
                    response()->json([
                        'error' => 'API request failed',
                        'message' => $e->getMessage(),
                    ], 500)
                );

            }

        }



}
