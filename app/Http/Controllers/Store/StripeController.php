<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserAddressApiController;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Event;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\StoreItem;
use App\Models\StoreHistory;
use App\Models\StoreKeep;
use App\Models\PointHistory;
use App\Models\User;
use App\Models\UserAddress;
/*
| =============================================
|  EC 購入手続き(Stripe) コントローラー
| =============================================
*/
class StripeController extends Controller
{
    /**
     * 決済(Stripe) チェックアウトの表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
    */
    public function checkout(Request $request)
    {
        $user = Auth::user();//顧客情報


        # エラーメッセージ
        $ids = $request->store_keep_ids;//カート商品ID($store_keep_ids)
        $store_keeps = StoreKeep::where('user_id',$user->id)->find($ids);//ログインユーザーのカート
        $store_keep_ids = !$store_keeps ? [] : $store_keeps->pluck('id')->toArray();//注文商品ID

        if ( $message = PurchaseController::ErrCheckMessage($request,$store_keeps) ){
            return redirect()->route('store.keep')
            ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-triangle']);
        }


        # 購入履歴の新規作成
        $store_history = self::createStoreHistory($request);

        # すぐに購入商品ページのURL(キャンセル用)
        $r_buynow_item = $request->r_buynow_item;


        # テスト用完了メソッド *後で消す！
        $test = env('APP_DEBUG');
        if( $test ){
            $session_id = 'stripe_checkout_session_id';//stripeセッションID

            # 決済完了のDB情報の登録メソッド
            self::completedMethod( $store_history, $user ,$session_id );

            return redirect()->route('store.purchase.comp', $store_history->code);
        }


        # チェックアウトの作成
        Stripe::setApiKey(config('stripe.secret_key'));

        $user        = Auth::user();
        $customer    = $user->createOrGetStripeCustomer();
        $productName = $store_history->product_name;      //決済名
        $amount      = $store_history->totalItemsPrice(); //購入するカート商品の[請求金額]

        $checkout_session = Session::create([
            'customer' => $customer->id,
            'customer_update' => ['address' => 'auto'],

            'payment_method_types' => [
                'card',
                // 'konbini',
                // 'customer_balance'
            ],

            'payment_method_options' => [
                'customer_balance' => [
                    'funding_type' => 'bank_transfer',
                    'bank_transfer' => [
                        'type' => 'jp_bank_transfer',
                    ],
                ],
                'card' => [//3Dセキュア
                    'request_three_d_secure' => 'any',
                ],
            ],

            'mode' => 'payment',


            'metadata' => [
                'store_history_id' => $store_history->id,
            ],


            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [

                        'name'  => $productName,

                    ],
                    'unit_amount' => $amount, // 金額（単位：円）
                ],
                'quantity' => 1,
            ]],

            // 'shipping_address_collection' => [ // 配送可能な国を指定
            //     'allowed_countries' => ['JP'],
            // ],

            'automatic_tax' => ['enabled' => false],

            'success_url' => route('store.purchase.comp', $store_history->code),
            'cancel_url'  => $r_buynow_item ?? route('store.keep'),
        ]);

        return redirect()->to($checkout_session->url);
    }



        /**
         * 購入履歴の新規作成メソッド
         *
         * @param  Request $request
         * @return StoreHistory $store_history
         */
        public function createStoreHistory($request)
        {
            # ログインユーザー
            $user = Auth::user();

            # 発送料金
            $shipped_price = PurchaseController::shippedPrice($request);

            # 注文商品($store_keeps)
            $ids = $request->store_keep_ids;
            $store_keeps = StoreKeep::where('user_id',$user->id)//ログインユーザーのカート
            ->find($ids);


            # お届け先アドレス

                $user_address_id = $request->user_address_id;//ユーザーアドレスID
                $user_address    = UserAddress::where('user_id',$user->id)->find($user_address_id);
                if( !$user_address ){ return \App::abort(404); }//データがないとき

                /* 選択のアドレスをデフォルトにする */
                $default_address    = (bool) $request->default_address;//選択のアドレスをデフォルトにする
                if( $default_address ){
                    UserAddressApiController::UpdateDeffaultAddress( $user_address_id );
                }


            # エラーメッセージ
            if ( $message = PurchaseController::ErrCheckMessage($request,$store_keeps) ){
                return \App::abort(404);
            }

            # 利用ポイント
            $use_point = $request->use_point;

            # 発送料金
            $shipped_price = PurchaseController::shippedPrice($request);

            # 注文履歴の作成
            $model = new StoreHistory;//算出用モデル
            $store_history = new StoreHistory([
                'code'             => $model->CreateCode(),
                'user_id'          => $user->id,         //ユーザー　　リレーション
                'user_address_id'  => $user_address->id, //発送先アドレス(保存用)
                'use_point'        => $use_point,        //利用ポイント(保存用)
                'redemption_point' => $model->sumItemsPointsRedemption($request->store_keep_ids),//還元ポイント(保存用)
                'shipped_price'    => $shipped_price,    //発送料金
                // 'done_at' => '',//決済完了日時
            ]);
            $store_history->save();


            # 購入商品と注文履歴のリレーション
            foreach ($store_keeps as $store_keep)
            {
                $store_keep->update([
                    'store_history_id'           => $store_history->id,    //販売履歴リレーション
                    'done_sum_price'             => $store_keep->sum_price,//注文時の合計価格
                    'done_sum_points_redemption' => $store_keep->sum_points_redemption,//注文時の合計還元ポイント
                    'done_store_item_name'       => $store_keep->store_item->name,//注文時の商品名
                    // 'done_at' => '',//決済完了日時
                ]);
            }



            // dd(
            //     [
            //         '商品の合計点数'    => $store_history->sumItemsCount(),
            //         '商品の還元ポイント' => $store_history->sumItemsPointsRedemption(),
            //         '利用ポイント'      => $store_history->use_point,
            //         '発送料金'         => $store_history->shipped_price,
            //         '商品の[小計]'      => $store_history->sumItemsPrice(),
            //         '商品の[請求金額]'  => $store_history->totalItemsPrice(),
            //         '購入商品'=> $store_history->store_keeps->toArray(),
            //     ]
            // );

            return $store_history;
        }



        /**
         * ECサイトの決済完了webhook
         * @param Array $event
         * @param Array $session
         * @return Json
        */
        public function webhook($event,$session)
        {
            // イベントタイプごとに処理を実行
            switch ($event->type) {

                ## クレジット・ウォレットで支払いが成功した場合の処理
                case 'checkout.session.completed':

                    /* 注文のフルフィルメントを実行 */
                    return $this->handleCheckoutSessionCompleted($session);
                    break;


                ## 銀行振込などの非同期型決済での、発送業務等のシステム連携処理
                case 'checkout.session.async_payment_succeeded':

                    /* 注文のフルフィルメントを実行 */
                    return $this->handleCheckoutSessionCompleted($session);
                    break;


                ##
                // case 'payment_intent.succeeded':
                //     break;

                default:
                    // 未知のイベントに対する処理
                    return response()->json(['message'=>'未知のイベントが処理されました。'], 200);
                    break;
            }

        }




        /**
         * 注文のフルフィルメントを実行するためのコード
         *
         * @param  Object $session //Stripe Checkout Session オブジェクト
         * @return Void
         */
        private function handleCheckoutSessionCompleted($session)
        {

            # Stripe側で決済が完了済(paid)でなければ、スキップ

                $paid = isset($session->payment_status) && $session->payment_status === 'paid';
                $response = ['message' => '決済が未完了です。'];
                if( !$paid ){ return response()->json( $response, 200 );}


            # CheckoutSessionが処理済みの時は、スキップ

                $session_id = $session['id'];
                $column = 'stripe_checkout_session_id';
                $previous_store_history = StoreHistory::where($column,$session_id)->first();

                $response = ['message' => '購入内容は処理済みです。'];
                if( $previous_store_history ){ return response()->json( $response, 200 );}


            # 客の情報

                $user = User::where('stripe_id', $session['customer'])
                ->withTrashed()//退会者を含む
                ->first();

                $response = ['message' => '一致するユーザー情報がありません。'];
                if( !$user ){ return response()->json( $response, 403 ); }


            # EC商品の購入履歴

                $sh_id         = $session['metadata']['store_history_id'];
                $store_history = StoreHistory::find($sh_id);

                $response = ['message' => '一致するEC商品の購入履歴がありません。','session.metadata.store_history_id'=>$sh_id];
                if( !$store_history ){ return response()->json( $response, 403 ); }



            # 決済完了のDB情報の登録メソッド
            self::completedMethod( $store_history, $user, $session_id );



            $response = ['message' => 'EC決済が完了しました。', 'store_history'=>$store_history];
            return response()->json( $response, 200 );
        }




        /**
         * 決済完了のDB情報の登録メソッド
        */
        public function completedMethod( $store_history, $user, $session_id )
        {

            # 利用ポイント( $use_point_history_id )

                $use_point_history_id = null;
                if( $store_history->use_point )
                {
                    $use_point_history = new PointHistory([
                        'user_id'   => $user->id,//ユーザー　リレーション
                        'value'     => - $store_history->use_point,//ポイント数
                        'reason_id' => 40,//商品購入時のポイント支払い分(減算)
                    ]);
                    $use_point_history->save();

                    $use_point_history_id = $use_point_history->id;
                }


            # ポイント還元( $redemption_point_history_id )

                $redemption_point_history_id = null;
                if( $store_history->redemption_point )
                {
                    $redemption_point_history = new PointHistory([
                        'user_id'   => $user->id,//ユーザー　リレーション
                        'value'     => $store_history->redemption_point,//ポイント数
                        'reason_id' => 50,//商品購入のポイント還元(加算)
                    ]);
                    $redemption_point_history->save();

                    $redemption_point_history_id = $redemption_point_history->id;
                }


            # EC商品の購入履歴の更新

                $store_history->update([
                    'done_at'                     => now(),      //決済完了日時
                    'stripe_checkout_session_id'  => $session_id,//Stripe決済完了ID
                    'use_point_history_id'        => $use_point_history_id,       //利用ポイント履歴ID
                    'redemption_point_history_id' => $redemption_point_history_id,//還元ポイント履歴ID
                    'state_id' => 11,//発送状況(発送待ち)
                ]);



            # 購入したカート商品情報の更新

                foreach ($store_history->store_keeps as $store_keep)
                {
                    $store_keep->update(['done_at'=> $store_history->done_at]);

                    # 商品情報の更新
                    $store_item = $store_keep->store_item;
                    $store_item->update([
                        'count'          => $store_item->count - $store_keep->count,//在庫数
                        'purchased_count'=> $store_item->purchased_count + $store_keep->count,//購入された回数
                    ]);
                }


            # サイト管理者へ完了メールの送信

                $subject = 'ID:'.$user->id.' '.$user->name.'様が、商品を購入しました';
                $email   = !config('app.debug') ? env('PAYMENT_COMP_EMAIL') : 't.sakai@tosuma.ltd';//ローカルではメール送信しない
                $inputs  = compact('user','store_history','email');
                Mail::to( $email ) //宛先
                ->send(new \App\Mail\SendHtmlMailMailable([
                    'inputs'  => $inputs, //入力変数
                    'view'    => 'emails.payment_ec_comp.admin' , //テンプレート
                    'subject' => $subject , //件名
                ]) );


            return true;
        }
    //
}
