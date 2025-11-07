<?php

namespace App\Http\Controllers\Store;
use  App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreKeep;
use App\Models\StoreItem;
use App\Models\StoreHistory;
/*
| =============================================
|  EC API 買い物カート コントローラー
| =============================================
*/
class StoreKeepApiController extends Controller
{
    /**
     * 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        # 交換商品情報の取得・検索
        $store_keeps = StoreKeep::forUserPublished($request)
        ->paginate(10);

        # 合計値
        $store_history   = new StoreHistory(['shipped_price'=>PurchaseController::shippedPrice($request)]);
        $store_keeps_ids = StoreKeep::forUserPublished($request)
        ->whereIn('id', $request->store_keep_ids)
        ->pluck('id')->toArray();
        $calcs = [

            ## 送料
            'shipped_price' => PurchaseController::shippedPrice($request),

            ## 購入するカート商品の合計点数
            'sum_items_count'             => $store_history->sumItemsCount( $store_keeps_ids ),

            ## 購入するカート商品の還元ポイント
            'sum_items_points_redemption' => $store_history->sumItemsPointsRedemption( $store_keeps_ids ),

            ## 購入するカート商品の[小計]
            'sum_items_price'             => $store_history->sumItemsPrice( $store_keeps_ids ),

            ## 購入するカート商品の[請求金額]　
            'total_items_price'           => $store_history->totalItemsPrice( $store_keeps_ids ),
        ];


        return response()->json( compact(
            'store_keeps', 'calcs',
        ) );
    }



    /**
     * カートへ入れる
     *
     * @param \Illuminate\Http\Request $request
     * @param StoreItem $store_item
     * @return \Illuminate\Http\Response
     */
    public function keep(Request $request, StoreItem $store_item)
    {
        # ログインユーザー
        $user = Auth::user();

        # エラーメッセージ
        if ( $message = $store_item->ErrCheckMessage($request->count)  )
        {
            return response()->json( compact( 'message' ) );
        }

        # エラーメッセージ-この商品が買い物カートに保存されているか？
        $store_keep = StoreKeep::forUserPublished()
        ->where('store_item_id',$store_item->id)
        ->first();
        if ( $store_keep ){
            $message = 'この商品は、既に買い物カートに入っています。';
            return response()->json( compact( 'message' ) );
        }




        # DBデータの新規登録
        $store_keep = new StoreKeep([
            'user_id'       => $user->id,      //ユーザー　　　リレーション
            'store_item_id' => $store_item->id,//販売アイテム　リレーション
            'count'         => $request->count,//注文数
        ]);
        $store_keep->save();


        return response()->json( compact( 'store_item' ) );
    }




    /**
     * 購入数の変更
     *
     * @param \Illuminate\Http\Request $request
     * @param StoreKeep $store_keep
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreKeep $store_keep)
    {
        # 購入数の更新
        $store_keep->count = $request->count;
        $store_keep->save();

        return response()->json( compact('store_keep') );
    }




    /**
     * 複数削除
     *
     * @param \Illuminate\Http\Request $request
     * @param StoreKeep $store_keep
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, StoreKeep $store_keep)
    {
        $store_keep->delete();
        return response()->json( ['message'=>'store_keep.destroy.comp'] );
    }


}
