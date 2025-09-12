<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminLogController;//操作ログ
use App\Http\Controllers\Method;//コントローラーメソッド

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\StoreItem;
use App\Models\Prize;
/*
| =============================================
|  ストアーAdmin API ストアー商品 コントローラー
| =============================================
*/
class AdminApiStoreItemController extends Controller
{
    /**
     * 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        self::apiBulk($request);          //一括削除処理


        # 交換商品情報の取得・検索
        $store_items = StoreItem::searchQuery($request)->paginate(20);


        # カテゴリー一覧
        $categories = GachaCategory::orderBy('created_at')->get();

        # 公開状態選択肢
        $published_statuses = [
            ['key'=>'published',      'label'=> '公開中'  ],
            ['key'=>'reserv_publish', 'label'=> '公開予約'],
            ['key'=>'an_publish',     'label'=> '未公開'  ],
            ['key'=>'sold_out',       'label'=> '売切れ'],
        ];

        # 並び替え選択肢
        $orders = StoreItem::orders();


        $inputs = $request->all();


        return response()->json( compact(
            'store_items', 'categories', 'published_statuses','orders','inputs'
        ) );
    }


        /** 一括処理 */
        public function apiBulk($request)
        {
            $store_items = StoreItem::whereIn('id',$request->store_item_ids)->get();

            # 一括削除
            if( $request->bulk ==='delete' ){
                foreach ($store_items as $store_item) { $store_item->delete(); }
            }

            # すべて公開に変更
            if( $request->bulk ==='published_true' ){
                foreach ($store_items as $store_item) { $store_item->update(['published_at'=>now()]); }
            }

            # すべて非公開に変更
            if( $request->bulk ==='published_false' ){
                foreach ($store_items as $store_item) { $store_item->update(['published_at'=>null]); }
            }

            # すべてスライド表示に変更
            if( $request->bulk ==='slide_true' ){
                foreach ($store_items as $store_item) { $store_item->update(['is_slide'=>true]); }
            }

            # すべてスライド非表示に変更
            if( $request->bulk ==='slide_false' ){
                foreach ($store_items as $store_item) { $store_item->update(['is_slide'=>false]); }
            }

        }


    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        # 更新元データ
        $store_item = StoreItem::find($request->id);


        # 入力データの加工
        $inputs = $request->only([
            'price'            ,//販売価格
            'count'            ,//在庫数
            'points_redemption',//還元ポイント
            'is_slide'         ,//スライド表示
            // 'published_at'     ,//公開日時
        ]);
        # 公開設定
        $inputs['published_at'] = AdminStoreItemController::processingPublished( $request, $store_item );



        # DBデータの更新
        $store_item->update($inputs);

        # 操作ログの更新
        AdminLogController::createLog( 'store_item.edit', $store_item->id );


        return response()->json(['store_item'=>$store_item,'requests'=>$inputs]);
    }

}
