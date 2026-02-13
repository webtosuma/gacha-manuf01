<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
// use GuzzleHttp\Client;
use App\Services\FincodeService;

// use App\Models\User;
// use App\Models\Admin;
// use App\Models\Gacha;
use App\Models\PointSail;
use App\Models\PointHistory;
use App\Models\TicketHistory;
use App\Models\FincodePaymentOrder;

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
     * 購入　手続き
     *
     * @param PointSail $point_sail
     * @param FincodeService $fincode
     * @return \Illuminate\Http\Response
    */
    public function payment(PointSail $point_sail, FincodeService $fincode)
    {
        # 購入ユーザー
        $user = Auth::user();


        # 仮注文作成
        $order = FincodePaymentOrder::create([
            'user_id' => $user->id,
            'point_sail_id' => $point_sail->id,
            'status' => 'pending',
        ]);


        $data = [

            'cancel_url'  => route('point_sail.fc.post'),

            'success_url' => route('point_sail.fc.callback', [
                'stripe_id' => (String) $point_sail->stripe_id,
                'order_id'  => (String) $order->id,
            ]),

            'transaction' => [
                'pay_type'       => ['Card'],
                'amount'         => (string)$point_sail->price,
                'client_field_1' => (string)$order->id,
            ],

            # ★ 3Dセキュア必須設定
            "card" => [
                "job_code"   => "CAPTURE",//売上確定
                "tds_type"   => "2",//3Dセキュア利用種別 2-3Dセキュア2.0を利用
                "tds2_type"  => "2",//3Dセキュア2.0非対応時の挙動設定 2-3エラー
                "tds2_email" => $user->email,
            ],
        ];
        $result = $fincode->createSession($data);



        return redirect($result['link_url']);
    }



    /**
     * 購入　手続き
     *
     * @param PointSail $point_sail
     * @param FincodeService $fincode
     * @return \Illuminate\Http\Response
    */
    public function callback(Request $request, FincodeService $fincode)
    {
        $orderId       = $request->order_id;
        $transactionId = $request->requestorTransId;
        $stripe_id     = $request->stripe_id;


        # 仮注文取得
        $order = FincodePaymentOrder::with(['user', 'pointSail'])
        ->findOrFail($orderId);


        # 販売ポイントIDのチェック
        if ( (string)$order->pointSail->stripe_id !== (string)$stripe_id ) { abort(403);}


        # 既に完了している場合（二重決済防止）
        if ($order->isCompleted()) { abort(403); }


        DB::transaction(function () use ($order, $transactionId) {

            $user       = $order->user;
            $point_sail = $order->pointSail;


            $rank_ratio = $user->now_rank
                ? $user->now_rank->point_sail_ratio
                : 1;


            # ポイント付与
            PointHistory::create([
                'user_id' => $user->id,
                'value'   => $point_sail->value * $rank_ratio,
                'price'   => $point_sail->price,
                'reason_id' => 11,
                'stripe_checkout_session_id' => $transactionId,
            ]);


            # チケット付与
            if ($point_sail->ticket > 0) {
                TicketHistory::create([
                    'user_id' => $user->id,
                    'value'   => $point_sail->ticket,
                    'reason_id' => 16,
                ]);
            }


            # 注文完了更新
            $order->update([
                'status' => 'completed',
                'fincode_transaction_id' => $transactionId,
            ]);


            # [紹介キャンペーン]ポイント付与
            CanpaingIntroductoryController::grant($user);

            # [キャンペーン]初回ポイント購入
            CanpaingFirstPointSailController::grant($user);

            
            # サイト管理者へ送信
            $subject = 'ID:'.$user->id.' '.$user->name.'様が、'.$point_sail->value.'pt購入しました';
            $email   = !config('app.debug') ? env('PAYMENT_COMP_EMAIL') : 't.sakai@tosuma.ltd';//ローカルではメール送信しない
            $inputs  = compact('user','point_sail','email');
            Mail::to( $email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs'  => $inputs, //入力変数
                'view'    => 'emails.payment_comp.admin' , //テンプレート
                'subject' => $subject , //件名
            ]) );


        });


        # 二重送信防止
        $request->session()->regenerateToken();


        return redirect()->route('point_sail.comp', $stripe_id );
    }


}
