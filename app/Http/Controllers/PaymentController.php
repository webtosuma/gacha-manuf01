<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Payjp\Charge;
use Payjp\Customer;
use Payjp\Payjp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
/**
 * =========================================
 *  ポイント購入　コントローラー
 * =========================================
*/
class PaymentController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $cardList = [];


        // 既にpayjpに登録済みの場合
        if (!empty($user->payjp_customer_id)) {
            // カード一覧を取得
            Payjp::setApiKey(config('payjp.secret_key'));
            $cardDatas = Customer::retrieve($user->payjp_customer_id)->cards->data;
            foreach ($cardDatas as $cardData) {
                $cardList[] = [
                    'cardNumber' =>  "**** **** **** {$cardData->last4}",
                    'brand' =>  $cardData->brand,
                    'exp_year' =>  $cardData->exp_year,
                    'exp_month' =>  $cardData->exp_month,
                    'name' =>  $cardData->name,
                    'id' =>  $cardData->id,
                ];
            }
        }
        // dd($cardList);
        return view('payment', compact('cardList'));
    }



    public function payment(Request $request)
    {
        if (
            empty($request->get('payjp-token')) && !$request->get('payjp_card_id')
        )
        { abort(404); }


        DB::beginTransaction();

        try {
            // ログインユーザー取得
            $user = Auth::user();

            // シークレットキーを設定
            Payjp::setApiKey(config('payjp.secret_key'));

            // ⭐️ 以前使用したカードを使う場合
            if (!empty($request->get('payjp_card_id'))) {
                $customer = Customer::retrieve($user['payjp_customer_id']);
                // 使用するカードを設定
                $customer->default_card = $request->get('payjp_card_id');
                $customer->save();
            }

            // ⭐️ 既にpayjpに登録済みの場合
            elseif (!empty($user['payjp_customer_id'])) {
                // カード情報を追加
                $customer = Customer::retrieve($user['payjp_customer_id']);
                $card = $customer->cards->create([
                    'card' => $request->get('payjp-token'),
                ]);
                // 使用するカードを設定
                $customer->default_card = $card->id;
                $customer->save();
            }

            // ⭐️ payjp未登録の場合
            else {
                // payjpで顧客新規登録 & カード登録
                $customer = Customer::create([
                    'card' => $request->get('payjp-token'),
                ]);


                // DBにcustomer_idを登録
                $user->payjp_customer_id = $customer->id;
                $user->save();
            }

            // ⭐️ 支払い処理
            Charge::create([
                "customer" => $customer->id,
                "amount" => 100,
                "currency" => 'jpy',
            ]);

            DB::commit();

            return redirect(route('payment'))->with('message', '支払いが完了しました');

        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();

            if(strpos($e,'has already been used') !== false) {
            return redirect()->back()->with('error-message', '既に登録されているカード情報です');
            }

            return redirect()->back();
        }
    }

}
