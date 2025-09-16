<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminPurchaseRequest;
use App\Models\Purchase;
use App\Models\Prize;
/*
|--------------------------------------------------------------------------
| Admin 買取表　コントローラー
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

        // $purchases = Purchase::searchQuery($request)->paginate(20);
        // dd($purchases[0]->toArray());


        # キーワード検索
        // $purchases = Purchase::whereHas('prize', function ($query) {
        //     $name = '1';
        //     $query->where('name', 'like',  '%'.$name.'%' );

        //     $category_id = 1;
        //     $query->where('category_id', $category_id );
        // })->get();

        // dd($purchases->toArray());



        return view('admin.purchase.index',compact(
            'category_id',
        ));
    }




    /**
     * 新規登録
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        # カテゴリーコードの認証
        $category_id = $request->category_id;

        return view('admin.purchase.create', compact(
            'category_id',
        ));
    }




    /**
     * 登録
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # 商品ID
        $prize_ids = $request->prize_ids;
        $prizes = Prize::find($prize_ids);


        # 登録
        foreach ($prizes as $prize)
        {
            $purchase = new Purchase([
                'prize_id'    => $prize->id,         //ガチャ用商品リレーション
            ]);
            $purchase->save();
        }


        # 操作ログの更新
        AdminLogController::createLog( 'purchase.create' );

        $request->session()->regenerateToken();// 二重送信防止


        # 返信メッセージ
        return redirect()->route('admin.purchase')
        ->with(['alert-success'=>'ガチャ商品を買取商品として登録しました']);
    }



    /**
     * 編集
     *
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    // public function edit(Purchase $purchase)
    // {
    //     return view('admin.purchase.edit',compact('purchase'));
    // }




    /**
     * 更新
     *
     * @param  AdminPurchaseRequest $request
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    // public function update(AdminPurchaseRequest $request, Purchase $purchase)
    // {
    //     # 入力データの加工
    //     $inputs = self::processingInputs( $request, $purchase );

    //     # DBデータの更新
    //     $purchase->update( $inputs );

    //     # 一回限定クーポンの複数発行
    //     if( $request->add_children_count )
    //     {
    //         for ($i=0; $i < $request->add_children_count; $i++) {
    //             $purchase_child = new PurchaseChild([ 'purchase_id' => $purchase->id]);
    //             $purchase_child->code = $purchase_child->CreateCode();//コードの生成
    //             $purchase_child->save();
    //         }
    //     }

    //     # 回数制限に達したか否か(is_done)
    //     $purchase = PurchaseController::isDoneFanc( $purchase );

    //     # 操作ログの更新
    //     AdminLogController::createLog( 'purchase.edit', $purchase->id );

    //     # 二重送信防止
    //     $request->session()->regenerateToken();


    //     # リダイレクト
    //     return redirect()->route('admin.purchase')
    //     ->with([ 'alert-warning'=>'クーポン情報を更新しました。']);
    // }




    /**
     * 削除
     *
     * @param  Request $request
     * @param  Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Request $request, Purchase $purchase)
    // {
    //     $purchase->delete();

    //     # 操作ログの更新
    //     AdminLogController::createLog( 'purchase.delete', $purchase->id );

    //     # 二重送信防止
    //     $request->session()->regenerateToken();

    //     # リダイレクト
    //     return redirect()->route('admin.purchase')
    //     ->with(['alert-danger'=>'クーポン情報を削除しました。']);
    // }








    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param Purchase $purchase //新規登録のとき===null
     * @return Array
     */
    // public function processingInputs( $request, $purchase=null )
    // {
    //     $purchase = $purchase ?? new Purchase();

    //     $inputs = $request->only(
    //         'is_use_code'    , //コードを利用するか
    //         'count'          ,//利用可能な回数
    //         'user_type'      ,//利用者の種類
    //     );


    //     # クーポンコードの生成
    //         $inputs['code'] = $purchase->code ?? $purchase->CreateCode();


    //     # エンコードコンポーネント入力情報のデコード処理（絵文字対策）
    //         $inputs['title'] = urldecode($request->title);


    //     # サービス(提供物)
    //     switch ($request->service)
    //     {
    //         /* ポイント */
    //         case 'point':
    //             $inputs['prize_id'] = null;
    //             $inputs['point']    = $request->point;
    //             break;

    //         /* 商品 */
    //         case 'prize':
    //             $prize = Prize::where('code',$request->prize_code)->first();
    //             $inputs['prize_id'] = $prize->id;
    //             $inputs['point']    = 0;
    //             break;

    //         /* */
    //     }


    //     # 利用回の種類
    //     $inputs['user_type'] = $request->user_type;
    //     if($inputs['user_type'] == 'user'){ $inputs['count'] = 1;}


    //     # 有効期限
    //         $inputs['expiration_at'] = $request->is_expiration
    //         ? ( $request->expiration_at.' 23:59:59' ) : null;


    //     # 公開設定
    //         $published_at = $purchase? $purchase->published_at :NULL;
    //         $is_published = $purchase? $purchase->is_published :NULL;//公開中か否か

    //         // 公開[1](前回が「公開」でないとき)
    //         if( $request->is_published==1 && !$is_published ){
    //             $published_at = now()->format('Y-m-d H:i:s');
    //         }
    //         // 公開予約[2]
    //         else if( $request->is_published==2 ){
    //             $published_at = str_replace('T',' ', $request->published_at );
    //         }
    //         // 非公開[0]
    //         else if( $request->is_published==0 ){
    //             $published_at = NULL;
    //         }

    //         $inputs['published_at'] = $published_at;

    //     //

    //     return $inputs;
    // }



}
