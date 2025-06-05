<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminCouponRequest;
use App\Models\Coupon;
use App\Models\CouponChild;
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
        $coupon_histories = CouponHistory::orderByDesc('created_at')
        ->paginate(20);

        return view('admin.coupon.history',compact('coupon_histories'));
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

        # 一回限定クーポンの複数発行
        if( $request->add_children_count )
        {
            for ($i=0; $i < $request->add_children_count; $i++) {
                $coupon_child = new CouponChild([ 'coupon_id' => $coupon->id]);
                $coupon_child->code = $coupon_child->CreateCode();//コードの生成
                $coupon_child->save();
            }
        }

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

        # 一回限定クーポンの複数発行
        if( $request->add_children_count )
        {
            for ($i=0; $i < $request->add_children_count; $i++) {
                $coupon_child = new CouponChild([ 'coupon_id' => $coupon->id]);
                $coupon_child->code = $coupon_child->CreateCode();//コードの生成
                $coupon_child->save();
            }
        }

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
     * @param  Request $request
     * @param  Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Coupon $coupon)
    {
        $coupon->delete();

        # 操作ログの更新
        AdminLogController::createLog( 'coupon.delete', $coupon->id );

        # 二重送信防止
        $request->session()->regenerateToken();

        # リダイレクト
        return redirect()->route('admin.coupon')
        ->with(['alert-danger'=>'クーポン情報を削除しました。']);
    }




    /**
     * コピー
     *
     * @param  Request $request
     * @param  Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request, Coupon $coupon)
    {
        # 値の加工
        $inputs = $coupon->only([
            'title'          ,//タイトル
            'prize_id'       ,//ガチャ商品ID
            'point'          ,//付与ポイント
            'count'          ,//利用可能な回数
            'user_type'      ,//利用者の種類
            'target_user_ids',//対象ユーザーのID
            'is_use_code'    ,//コードを利用するか
            'expiration_at'  ,//有効期限
        ]);
        $inputs['code'] = $coupon->CreateCode();//コードの生成
        $inputs['is_done'] = 0;        //回数制限に達したか否か
        $inputs['published_at'] = null;//公開日時


        # DBデータの新規登録
        $copy_coupon = new Coupon( $inputs );
        $copy_coupon->save();

        # 操作ログの更新
        AdminLogController::createLog( 'coupon.copy', $coupon->id );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.coupon')
        ->with(['alert-success'=>'クーポン情報をコピーしました。(非公開)']);
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
            'is_use_code'    , //コードを利用するか
            'count'          ,//利用可能な回数
            'user_type'      ,//利用者の種類
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


        # 利用回の種類
        $inputs['user_type'] = $request->user_type;
        if($inputs['user_type'] == 'user'){ $inputs['count'] = 1;}


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
