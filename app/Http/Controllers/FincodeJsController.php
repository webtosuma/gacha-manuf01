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
|  ポイント購入 (fincode JS) コントローラー
| =============================================
*/
class FincodeJsController extends Controller
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


        return view('point_sail.fincode_js.index',compact('point_sails', 'rank_ratio'));
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
        # fincode顧客情報
        $cosutomer = self::getCustomer();
        $card_list = $cosutomer['card_list'];

        // dd( $card_list );
        $card_id     = $card_list[0]['id'];
        $customer_id = $card_list[0]['customer_id'];
        $holder_name = $card_list[0]['holder_name'];




        /* 決済の登録メソッド */
        $crate_data = self::createPayent( $point_sail );
        $order_id  = $crate_data['id'];
        $access_id = $crate_data['access_id'];

        /* 決済の実行 */
        self::executionPayment( $order_id, $access_id, $card_id, $customer_id, $holder_name );


        return view('point_sail.fincode_js.payment', compact('point_sail', 'card_list' ) );
        // return view('point_sail.fincode_js.payment', compact('point_sail',) );
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
        $customerId = 'c_ibc2eZGsRyGyG2eQPKDmLw';//削除

        $DATA = [
            'customer_id' => $customerId,
            "tds_type"   => "2",//3Dセキュア利用種別 2-3Dセキュア2.0を利用
            "tds2_type"  => "2",//3Dセキュア2.0非対応時の挙動設定 2-3エラー
            "tds2_email" => $user->email,

            //成功リダイレクトパス
            'success_url' => route('point_sail.payment', $point_sail ),

            //失敗リダイレクトパス
            'cancel_url'  => route('point_sail.post'),

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
                return response()->json($responseBody);

            } catch (\Exception $e) {
                // エラー処理
                return response()->json([
                    'error' => 'API request failed',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }



        /**
         * 顧客取得メソッド
         */
        public function getCustomer()
        {
            $user = Auth::user();
            $customerId = $user->id;
            $customerId = 'c_ibc2eZGsRyGyG2eQPKDmLw';//削除

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
         * 顧客更新メソッド
        */
        public function updateCustomer()
        {
            $user = Auth::user();
            $customerId = $user->id;
            $customerId = 'c_ibc2eZGsRyGyG2eQPKDmLw';//削除

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
                return response()->json($responseBody);

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

                // return response()->json([
                //     'error' => 'API request failed',
                //     'message' => $e->getMessage(),
                // ], 500);
            }
        }



        /**
         * 決済の登録メソッド
        */
        public function createPayent( $point_sail )
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
                "job_code"   => "CAPTURE",//売上確定
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
            }
        }



        /**
         * 決済の実行メソッド
         */
        public function executionPayment( $order_id, $access_id, $card_id, $customer_id, $holder_name )
        {
            // dd($order_id);

            $API_KEY  = 'Bearer '.config('fincode.secret_key','');
            $BASE_URL = config('fincode.base_url','');
            $endpoint = "/v1/payments/{$order_id}";
            // $endpoint = "/v1/payments/{$order_id}/capture";
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
                "method"      => "1",   //支払方法
                "tds2_ret_url"=> route('point_sail.failure'),//加盟店戻りURL

                "access_id"   => $access_id,
                "card_id"     => $card_id,
                "customer_id" => $customer_id,
                "holder_name" => $holder_name,//3Dセキュア必須

                "job_code"   => "CAPTURE",//売上確定
                "tds_type"   => "2",//3Dセキュア利用種別 2-3Dセキュア2.0を利用
                "tds2_type"  => "2",//3Dセキュア2.0非対応時の挙動設定 2-3エラー
                "tds2_email" => $user->email,
            ];


            try {
                $response = $client->put($endpoint, [
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
            }
        }


}
