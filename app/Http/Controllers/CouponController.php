<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use App\Models\CouponChild;
use App\Models\CouponHistory;
use App\Models\PointHistory;
use App\Models\UserPrize;
/*
|--------------------------------------------------------------------------
| クーポン
|--------------------------------------------------------------------------
*/
class CouponController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Coupon::query();

            //ユーザー表示用スコープ
            $query->forUserPublished();

            //配布クーポンのみ
            $query->where('is_use_code',0);

        $coupons = $query->paginate(10);


        return view('coupon.index',compact('coupons'));
    }



    /**
     * 詳細
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        # コード
        $code = $request->code;

        # クーポン情報
        $coupon = Coupon::forUserPublished()
        ->where('code',$code)->first();

        # 一回限定で複数発行のクーポン
        $coupon_child = CouponChild::where('is_done',0)
        ->where('code',$code)->first();
        $coupon = $coupon_child ? $coupon_child->coupon : $coupon;


        if(!$coupon){
            $message = 'このクーポンコードを利用することはできません。';
            return redirect()->back()
            ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-circle']);
        }

        if(!$coupon->remaining_count){
            $message = 'このクーポンコードは終了しました。';
            return redirect()->back()
            ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-circle']);
        }


        return view('coupon.show',compact('coupon','code'));
    }



    /**
     * クーポン利用
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function used(Request $request)
    {
        # コード
        $code = $request->code;

        # クーポン情報
        $coupon = Coupon::forUserPublished()
        ->where('code',$code)->first();

        # 一回限定で複数発行のクーポン
        $coupon_child = CouponChild::where('is_done',0)
        ->where('code',$code)->first();
        $coupon = $coupon_child ? $coupon_child->coupon : $coupon;

        if(!$coupon){
            $message = 'このクーポンコードを利用することはできません。';
            return redirect()->back()
            ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-circle']);
        }

        if(!$coupon->remaining_count){
            $message = 'このクーポンコードは終了しました。';
            return redirect()->back()
            ->with(['alert-warning'=>$message,'icon'=>'bi-exclamation-circle']);
        }

        # ユーザー情報
        $user = Auth::user();


        # サービス(商品orポイント)による分岐
        switch ($coupon->service)
        {
            /* 商品提供 */
            case 'prize':

                // dd('hoge');
                $user_prize = new UserPrize([
                    'user_id'   => $user ->id,        //ユーザーリレーション
                    'prize_id'  => $coupon->prize_id, //商品リレーション
                ]);
                $user_prize->save();
                break;


            /* ポイント提供 */
            case 'point':

                $point_history = new PointHistory([
                    'user_id'   => $user ->id,     //ユーザーリレーション
                    'value'     => $coupon->point, //ポイント数
                    'reason_id' => 17,             //入出理由ID
                ]);
                $point_history->save();
                break;

            /* */
        }

        # クーポン履歴の保存
        $coupon_history = new CouponHistory([
            'user_id'         => $user ->id,//ユーザーリレーション
            'coupon_id'       => $coupon->id,//クーポンリレーション
            'user_prize_id'   => isset($user_prize)    ? $user_prize->id    : null ,
            'point_history_id'=> isset($point_history) ? $point_history->id : null ,
        ]);
        $coupon_history->save();


        # 回数制限に達したか否か(is_done)
        $coupon = CouponController::isDoneFanc( $coupon );

        # 一回限定で複数発行のクーポンを利用のとき
        if($coupon_child){ $coupon_child->update(['is_done'=>1]); };

        # 二重送信防止
        $request->session()->regenerateToken();


        #
        return redirect()->route('coupon.comp',$coupon_history);
    }



    /**
     * 完了
     *
     * @param  Request $request
     * @param  CouponHistory $coupon_history
     * @return \Illuminate\Http\Response
     */
    public function comp(Request $request, CouponHistory $coupon_history)
    {
        # ユーザー認証
        $user = Auth::user();
        if( $coupon_history->user->id!=$user->id ){ return \App::abort(404); }


        # クーポン情報
        $coupon = $coupon_history->coupon;

        # 履歴表示か否か
        $is_history = isset($request->is_history);

        # 背景画像
        $bg_image = AdminBackGroundController::getBgResult();

        # メッセージ
        $message = $coupon->prize
        ? "『{$coupon->prize->name}』を取得しました！"
        : "『{$coupon->point}pt』を取得しました！";

        return view('coupon.comp',compact(
            'coupon_history','coupon','bg_image','message','is_history'
        ));
    }




    /**
     * 履歴一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        $user = Auth::user();
        $coupon_histories = CouponHistory::where('user_id',$user->id)
        ->orderByDesc('created_at')
        ->paginate(10);

        return view('coupon.history',compact('coupon_histories'));
    }



        /**
         * 回数制限に達したか否か(is_done)
         * @return Coupon $coupon
        */
        public static function isDoneFanc($coupon)
        {
            if( ! ($coupon->count && $coupon->user_type == 'all_user') ){ return $coupon; }

            $history_count = CouponHistory::where('coupon_id',$coupon->id)->count();
            $count = $coupon->count - $history_count;

            $coupon->is_done = $count>0 ? 0 : 1;
            $coupon->save();

            return $coupon;
        }

}
