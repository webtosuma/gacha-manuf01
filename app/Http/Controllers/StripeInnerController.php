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
|  ポイント購入 (Stripe・プロジェクト内で購入処理の実行) コントローラー
| =============================================
*/
class StripeInnerController extends Controller
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
        return view('point_sail.stripe.payment',compact('point_sail'));
    }



    /**
     * 購入処理
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PointSail $point_sail
    */
    public function store(Request $request,PointSail $point_sail)
    {
        Stripe::setApiKey(config('stripe.secret_key'));
        $user = Auth::user();



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


            # Stripeの決済処理
            Charge::create([
                'source' => $request->stripeToken,
                'amount' => $point_sail->price,
                'currency' => 'jpy',
            ]);
            DB::commit();


            // # ポイント購入完了メールの送信
            $request->user       = $user;
            $request->point_sail = $point_sail;
            $request->email      = env('PAYMENT_COMP_EMAIL');
            SendMailController::PaymentComp( $request );


            # [キャンペーン]初回ポイント購入
            CanpaingFirstPointSailController::grant($user);

            # [紹介キャンペーン]ポイント付与
            CanpaingIntroductoryController::grant($user);


            // 二重送信防止
            $request->session()->regenerateToken();



        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->with('flash_alert', '決済に失敗しました！('. $e->getMessage() . ')');
        }

        return redirect()->route('point_sail.comp',$point_sail->stripe_id);
    }



    /**
     * ポイント購入　完了
     * @param String $stripe_id
     * @return \Illuminate\View\View
    */
    public function comp($stripe_id)
    {
        $user = Auth::user();


        $point_sail = PointSail::where('stripe_id',$stripe_id)->first();

        return $point_sail
        ? view('point_sail.comp', compact('point_sail'))
        : \App::abort(404);
    }
}
