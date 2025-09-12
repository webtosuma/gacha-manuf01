<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminPurchaseRequest;
use App\Models\Purchase;
use App\Models\Prize;
/*
|--------------------------------------------------------------------------
| Admin クーポン　コントローラー
|--------------------------------------------------------------------------
*/
class AdminPurchaseController extends Controller
{
    /**
     * 一覧
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category_id = $request->category_id;

        # [ルーティング]ガチャ商品から登録(未登録の場合は、非表示)
        $r_prize_create = Prize::first() ? route('admin.store_item.prize.create') : null;

        return view('admin.purchase.index',compact(
            'category_id', 'r_prize_create',
        ));
    }




    /**
     * 履歴一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        $purchase_histories = PurchaseHistory::orderByDesc('created_at')
        ->paginate(20);

        return view('admin.purchase.history',compact('purchase_histories'));
    }




    /**
     * 新規登録
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchase = new Purchase([
            'point' => 0,
            'count' => 1,
            'user_type' => 'user',
        ]);

        return view('admin.purchase.create',compact('purchase'));
    }



    /**
     * 登録
     *
     * @param  AdminPurchaseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminPurchaseRequest $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );

        # DBデータの新規登録
        $purchase = new Purchase( $inputs );
        $purchase->save();

        # 一回限定クーポンの複数発行
        if( $request->add_children_count )
        {
            for ($i=0; $i < $request->add_children_count; $i++) {
                $purchase_child = new PurchaseChild([ 'purchase_id' => $purchase->id]);
                $purchase_child->code = $purchase_child->CreateCode();//コードの生成
                $purchase_child->save();
            }
        }

        # 操作ログの更新
        AdminLogController::createLog( 'purchase.create', $purchase->id );

        # 二重送信防止
        $request->session()->regenerateToken();

        # 返信メッセージ
        return redirect()->route('admin.purchase')
        ->with([ 'alert-primary'=>'クーポン情報を新規登録しました。',]);
    }



    /**
     * 編集
     *
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        // dd($purchase->remaining_count);
        return view('admin.purchase.edit',compact('purchase'));
    }




    /**
     * 更新
     *
     * @param  AdminPurchaseRequest $request
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(AdminPurchaseRequest $request, Purchase $purchase)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request, $purchase );

        # DBデータの更新
        $purchase->update( $inputs );

        # 一回限定クーポンの複数発行
        if( $request->add_children_count )
        {
            for ($i=0; $i < $request->add_children_count; $i++) {
                $purchase_child = new PurchaseChild([ 'purchase_id' => $purchase->id]);
                $purchase_child->code = $purchase_child->CreateCode();//コードの生成
                $purchase_child->save();
            }
        }

        # 回数制限に達したか否か(is_done)
        $purchase = PurchaseController::isDoneFanc( $purchase );

        # 操作ログの更新
        AdminLogController::createLog( 'purchase.edit', $purchase->id );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.purchase')
        ->with([ 'alert-warning'=>'クーポン情報を更新しました。']);
    }




    /**
     * 削除
     *
     * @param  Request $request
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Purchase $purchase)
    {
        $purchase->delete();

        # 操作ログの更新
        AdminLogController::createLog( 'purchase.delete', $purchase->id );

        # 二重送信防止
        $request->session()->regenerateToken();

        # リダイレクト
        return redirect()->route('admin.purchase')
        ->with(['alert-danger'=>'クーポン情報を削除しました。']);
    }




    /**
     * コピー
     *
     * @param  Request $request
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request, Purchase $purchase)
    {
        # 値の加工
        $inputs = $purchase->only([
            'title'          ,//タイトル
            'prize_id'       ,//ガチャ商品ID
            'point'          ,//付与ポイント
            'count'          ,//利用可能な回数
            'user_type'      ,//利用者の種類
            'target_user_ids',//対象ユーザーのID
            'is_use_code'    ,//コードを利用するか
            'expiration_at'  ,//有効期限
        ]);
        $inputs['code'] = $purchase->CreateCode();//コードの生成
        $inputs['is_done'] = 0;        //回数制限に達したか否か
        $inputs['published_at'] = null;//公開日時


        # DBデータの新規登録
        $copy_Purchase = new Purchase( $inputs );
        $copy_Purchase->save();

        # 操作ログの更新
        AdminLogController::createLog( 'purchase.copy', $purchase->id );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.purchase')
        ->with(['alert-success'=>'クーポン情報をコピーしました。(非公開)']);
    }




    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param Purchase $purchase //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $purchase=null )
    {
        $purchase = $purchase ?? new Purchase();

        $inputs = $request->only(
            'is_use_code'    , //コードを利用するか
            'count'          ,//利用可能な回数
            'user_type'      ,//利用者の種類
        );


        # クーポンコードの生成
            $inputs['code'] = $purchase->code ?? $purchase->CreateCode();


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
            $published_at = $purchase? $purchase->published_at :NULL;
            $is_published = $purchase? $purchase->is_published :NULL;//公開中か否か

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
