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
use App\Models\TicketHistory;

use App\Http\Controllers\CanpaingIntroductoryController;//お友達紹介キャンペーン
use App\Http\Controllers\CanpaingFirstPointSailController;//初回ポイント購入キャンペーン

/*
| =============================================
|  ポイント購入 (Stripe) サブスク コントローラー
| =============================================
*/
class StripSubscriptionController extends Controller
{
    /**
     * サブスク　一覧
     * @return \Illuminate\View\View
    */
    public function index()
    {
        $subscriptions = [];


        return view('point_sail.subscription.index',compact('subscriptions'));
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


        # 商品情報
        $price_id = 'price_1P1fYnKoJdkajOL0nIegnltI';

        $checkout_session = Session::create([

            'customer' => $customer->id, //顧客ID
            'customer_update'=>['address'=> 'auto'],
            'payment_method_types' => [ 'card',],

            'line_items' => [[
                'price' => $price_id,
                'quantity' => 1,
            ]],

            'mode' => 'subscription',
            'success_url' => route('point_sail.comp',$point_sail->stripe_id),//成功リダイレクトパス
            'cancel_url'  => route('point_sail'),//失敗リダイレクトパス
        ]);

        // $checkout_session = Session::create([

        //     'customer' => $customer->id, //顧客ID
        //     'customer_update'=>['address'=> 'auto'],

        //     'payment_method_types' => [
        //         'card',
        //         // 'konbini',
        //         'customer_balance'
        //     ],

        //     'payment_method_options' => [
        //         'customer_balance'=> [
        //             'funding_type'=> 'bank_transfer',
        //             'bank_transfer'=> [
        //                 'type'=> 'jp_bank_transfer',
        //             ],
        //         ],
        //     ],

        //     'mode' => 'payment',
        //     'line_items' => [[
        //         'price' => $point_sail->stripe_id,
        //         'quantity' => 1,
        //     ]],
        //     'automatic_tax' => [ 'enabled' => true, ],

        //     'success_url' => route('point_sail.comp',$point_sail->stripe_id),//成功リダイレクトパス
        //     'cancel_url'  => route('point_sail'),//失敗リダイレクトパス
        // ]);

        return redirect()->to($checkout_session->url);
    }



}
