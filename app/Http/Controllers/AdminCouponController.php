<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminCouponRequest;
use App\Models\Coupon;
use App\Models\CouponHistory;
use App\Models\Prize;
/*
|--------------------------------------------------------------------------
| Admin クーポン　コントローラー
|--------------------------------------------------------------------------
*/
class AdminCouponController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::orderByDesc('created_at')->get();

        return view('admin.coupon.index',compact('coupons'));
    }




    /**
     * 履歴一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        $coupon_histories = CouponHistory::orderByDesc('created_at')->get();

        return view('admin.coupon.history');
    }




    /**
     * 新規登録
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $coupon = new Coupon([
            'point' => 0,
            'count' => 1,
            'user_type' => 'user',
        ]);

        return view('admin.coupon.create',compact('coupon'));
    }



    /**
     * 登録
     *
     * @param  AdminCouponRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCouponRequest $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );

        # DBデータの新規登録
        $coupon = new Coupon( $inputs );
        $coupon->save();

        # 操作ログの更新
        AdminLogController::createLog( 'coupon.create', $coupon->id );

        # 二重送信防止
        $request->session()->regenerateToken();

        # 返信メッセージ
        return redirect()->route('admin.coupon')
        ->with([ 'alert-primary'=>'クーポン情報を新規登録しました。',]);
    }



    /**
     * 編集
     *
     * @param  Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        // dd($coupon->remaining_count);
        return view('admin.coupon.edit',compact('coupon'));
    }




    /**
     * 更新
     *
     * @param  AdminCouponRequest $request
     * @param  Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(AdminCouponRequest $request, Coupon $coupon)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request, $coupon );

        # DBデータの更新
        $coupon->update( $inputs );

        # 回数制限に達したか否か(is_done)
        $coupon = CouponController::isDoneFanc( $coupon );

        # 操作ログの更新
        AdminLogController::createLog( 'coupon.edit', $coupon->id );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.coupon')
        ->with([ 'alert-warning'=>'クーポン情報を更新しました。']);
    }




    /**
     * 削除
     *
     * @param  Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        # 操作ログの更新
        AdminLogController::createLog( 'coupon.delete', $coupon->id );


        # リダイレクト
        return redirect()->route('admin.coupon')
        ->with(['alert-danger'=>'クーポン情報を削除しました。']);
    }




    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param Coupon $coupon //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $coupon=null )
    {
        $coupon = $coupon ?? new Coupon();

        $inputs = $request->only(
            'is_use_code'  , //コードを利用するか
        );


        # クーポンコードの生成
            $inputs['code'] = $coupon->code ?? $coupon->CreateCode();


        # エンコードコンポーネント入力情報のデコード処理（絵文字対策）
            $inputs['title'] = urldecode($request->title);


        # サービス(提供物)
        switch ($request->service)
        {
            /* ポイント */
            case 'point':
                $inputs['prize_id'] = null;
                $inputs['point']    = $request->point;
                break;

            /* 商品 */
            case 'prize':
                $prize = Prize::where('code',$request->prize_code)->first();
                $inputs['prize_id'] = $prize->id;
                $inputs['point']    = 0;
                break;

            /* */
        }


        # 利用回数制限
        switch ($request->is_count)
        {
            /* 設定する */
            case 1:
                $inputs['user_type'] = $request->user_type;
                $inputs['count']     = $request->count;
                break;

            /* 設定しない */
            default:
                $inputs['user_type'] = '';
                $inputs['count']     = 0;
                break;

            /* */
        }


        # 有効期限
            $inputs['expiration_at'] = $request->is_expiration
            ? ( $request->expiration_at.' 23:59:59' ) : null;


        # 公開設定
            $published_at = $coupon? $coupon->published_at :NULL;
            $is_published = $coupon? $coupon->is_published :NULL;//公開中か否か

            // 公開[1](前回が「公開」でないとき)
            if( $request->is_published==1 && !$is_published ){
                $published_at = now()->format('Y-m-d H:i:s');
            }
            // 公開予約[2]
            else if( $request->is_published==2 ){
                $published_at = str_replace('T',' ', $request->published_at );
            }
            // 非公開[0]
            else if( $request->is_published==0 ){
                $published_at = NULL;
            }

            $inputs['published_at'] = $published_at;

        //

        return $inputs;
    }


}
