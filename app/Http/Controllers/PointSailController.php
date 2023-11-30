<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Payjp\Charge;
use Payjp\Customer;
use Payjp\Payjp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PointSail;
use App\Models\PointHistory;
/*
| =============================================
|  ポイント購入 コントローラー
| =============================================
*/
class PointSailController extends Controller
{

    /** ログイン中のみ処理可能 @return void */
    public function __construct(){ $this->middleware('auth');}

    /*
    |--------------------------------------------------------------------------
    | ポイント購入　ページ
    |--------------------------------------------------------------------------
    */


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
         * @return \Illuminate\View\View
        */
        public function payment(PointSail $point_sail)
        {
            $cardList = self::UserCardList();//ユーザーが登録したカード一覧の取得
            return view('point_sail.payment', compact('point_sail','cardList'));
        }



        /**
         * 新規登録
         * @param \Illuminate\Http\Request $request
         * @param \App\Models\PointSail $point_sail
         * @return \Illuminate\View\View
        */
        public function create( Request $request, PointSail $point_sail )
        {
            # 新規作成メソッド
            self::MethodCreate( $request );

            # 購入　手続きへリダイレクト
            return redirect()->route('point_sail.payment',$point_sail);
        }



        /**
         * ポイント購入　購入手続き
         * @param \Illuminate\Http\Request $request
         * @param \App\Models\PointSail $point_sail
         * @return \Illuminate\View\View
        */
        public function payment_post(Request $request, PointSail $point_sail)
        {
            # ログインユーザー取得
            $user = Auth::user();

            if (
                empty($request->get('payjp-token'))
                && !$request->get('payjp_card_id')
            )
            { abort(404); }



            DB::beginTransaction();
            try {

                # ポイント履歴の登録
                $point_history = new PointHistory([
                    'user_id'   => $user->id,          //ユーザー　リレーション
                    'value'     => $point_sail->value, //ポイント数
                    'price'     => $point_sail->price, //販売価格(税込み)
                    'reason_id' => 11 //入出理由ID
                ]);
                $point_history->save();


                # カード情報の取得と支払い処理
                self::PayjpCharge( $request, $amount=$point_sail->price );

                DB::commit();
                return redirect(route('point_sail.comp',$point_history));


            } catch (\Exception $e) {

                Log::error($e);
                DB::rollback();

                if(strpos($e,'has already been used') !== false) {
                    return redirect()->back()->with('error-message', '既に登録されているカード情報です');
                }

                return redirect()->back()->with('error-message', 'エラーが発生しました。');
            }

        }




        /**
         * ポイント購入　完了
         * @param \App\Models\PointHistory $point_history
         * @return \Illuminate\View\View
        */
        public function comp(PointHistory $point_history)
        {
            return view('point_sail.comp', compact('point_history'));
        }
    /*
    |--------------------------------------------------------------------------
    | クラス内で利用するメソッド
    |--------------------------------------------------------------------------
    */

        /**
         * ユーザーが登録したカード一覧の取得
         * @return Array
         */
        public static function UserCardList()
        {
            $user = Auth::user();
            $cardList = [];

            # 既にpayjpに登録済みの場合
            if (!empty($user->payjp_customer_id)) {
                // カード一覧を取得
                Payjp::setApiKey(config('payjp.secret_key'));
                $cardDatas = Customer::retrieve($user->payjp_customer_id)->cards->data;
                foreach ($cardDatas as $cardData) {
                    $cardList[] = [
                        'cardNumber' =>  "**** **** **** {$cardData->last4}",
                        'brand'      =>  $cardData->brand,
                        'exp_year'   =>  $cardData->exp_year,
                        'exp_month'  =>  $cardData->exp_month,
                        'name'       =>  $cardData->name,
                        'id' =>  $cardData->id,
                    ];
                }
            }

            return $cardList;
        }


        /**
         * カード情報の取得と支払い処理
         * @param \Illuminate\Http\Request $request
         * @param Integer $amount //入金される金額（税込み）
         * @return Void
         */
        public static function PayjpCharge( $request, $amount )
        {
            # ログインユーザー取得
            $user = Auth::user();

            # シークレットキーを設定
            Payjp::setApiKey(config('payjp.secret_key'));

            # 以前使用したカードを使う場合
            if ( !empty( $request->get('payjp_card_id') ) ) {
                $customer = Customer::retrieve($user['payjp_customer_id']);
                // 使用するカードを設定
                $customer->default_card = $request->get('payjp_card_id');
                $customer->save();
            }

            # 新規カード作成＆既にpayjpに登録済みの場合
            elseif ( !empty( $user['payjp_customer_id'] )) {
                // カード情報を追加
                $customer = Customer::retrieve( $user['payjp_customer_id'] );
                $card = $customer->cards->create([
                    'card' => $request->get('payjp-token'),
                ]);
                // 使用するカードを設定
                $customer->default_card = $card->id;
                $customer->save();
            }

            # 新規カード作成＆payjp未登録の場合
            else {
                // payjpで顧客新規登録 & カード登録
                $customer = Customer::create([
                    'card' => $request->get('payjp-token'),
                ]);
                // DBにcustomer_idを登録
                $user->payjp_customer_id = $customer->id;
                $user->save();
            }


            # 支払い処理
            Charge::create([
                "customer" => $customer->id,
                "amount"   => $amount,//入金される金額（税込み）
                "currency" => 'jpy',
            ]);
        }


        /**
         * 新規登録メソッド
         * @param \Illuminate\Http\Request $request
         * @return Void
        */
        public static function MethodCreate( Request $request )
        {
            # ログインユーザー取得
            $user = Auth::user();

            # シークレットキーを設定
            Payjp::setApiKey(config('payjp.secret_key'));

            # 新規カード作成＆既にpayjpに登録済みの場合
            if ( !empty( $user['payjp_customer_id'] )) {
                // カード情報を追加
                $customer = Customer::retrieve( $user['payjp_customer_id'] );
                $card = $customer->cards->create([
                    'card' => $request->get('payjp-token'),
                ]);
                // 使用するカードを設定
                $customer->default_card = $card->id;
                $customer->save();
            }

            # 新規カード作成＆payjp未登録の場合
            else {
                // payjpで顧客新規登録 & カード登録
                $customer = Customer::create([
                    'card' => $request->get('payjp-token'),
                ]);
                // DBにcustomer_idを登録
                $user->payjp_customer_id = $customer->id;
                $user->save();
            }
        }



        /**
         * 削除メソッド
         * @param \Illuminate\Http\Request $request
         * @return Void
        */
        public static function MethodDestory( $request )
        {
            # ログインユーザー取得
            $user = Auth::user();

            # シークレットキーを設定
            Payjp::setApiKey(config('payjp.secret_key'));

            $payjp_card_id = $request->get('payjp_card_id');

            $customer = Customer::retrieve( $user['payjp_customer_id'] );
            $card = $customer->cards->retrieve( $payjp_card_id );
            $card->delete();
        }
    //
}
