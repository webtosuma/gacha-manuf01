<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gacha;
use App\Models\PointSail;

/*
| =============================================
|  ポイント購入 (共通) コントローラー
| =============================================
*/
class PurchasePointController extends Controller
{
    /**
     * ポイント　一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function index(Request $request)
    {
        # 支払いタイプ
        $payment_type_key   = env('FINCODE_KEY') ? 'fincode.card' : null ;
        $payment_type_key   = env('STRIPE_KEY')  ? 'stripe.card'  : $payment_type_key ;
        $payment_type_key   = $request->payment_type_key ?? $payment_type_key;

        $payment_type_label = $request->payment_type_label;
        $test = config('app.debug');

        # 販売用ポイント情報取得
        $point_sails = PointSail::where('is_subscription',false)//サブスク以外
        ->where('is_published',1)//公開ずみのみ
        ->orderBy('value','asc')//ポイントが低い順
        ->get();

        
        # 支払いタイプ別支払いページURL
        switch ( $payment_type_key ) {
            case 'fincode.card' : //fincode クレジットカード
                foreach ($point_sails as $point_sail) {
                    $point_sail->r_payment = route('point_sail.fc.payment', $point_sail);
                    $point_sail->fincode   = true;//fincodeボタン
                }
                break;

            case 'fincode.card.jcb' : //fincode クレジットカード JCB
                foreach ($point_sails as $point_sail) {
                    $point_sail->r_payment = route('point_sail.fc.payment', $point_sail);
                    $point_sail->fincode   = true;//fincodeボタン
                }
                break;

            case 'paypay.paypay': //PayPay PayPay
                foreach ($point_sails as $point_sail) {
                    $point_sail->r_payment = route('point_sail.paypay.payment', $point_sail);
                }
                break;

            default:
                foreach ($point_sails as $point_sail) {
                    $point_sail->r_payment = route('point_sail.payment', $point_sail);
                }
                break;
        }

        # ランクごとのポイント還元率
        $rank_ratio = Auth::check() && Auth::user()->now_rank && config('u_rank_ticket.user_rank',false)
        ? Auth::user()->now_rank->point_sail_ratio : 1 ;


        return view('point_sail.index',compact(
            'point_sails', 'rank_ratio','payment_type_key','payment_type_label','test',
        ));
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
}
