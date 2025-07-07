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


        // /**
        //  * 交換商品情報の取得・検索
        //  * @return Store $prizes
        //  */
        // public static function SearchQuery($request)
        // {
        //     # 商品情報の取得
        //     $query = StoreItem::query();

        //         #　キーワード検索
        //         if( $request->keyword )
        //         {
        //             $query->where( 'item_name','like','%'.$request->keyword.'%' );
        //         }

        //         # カテゴリーの選択
        //         if(  $request->category_id ){
        //             $query->where('category_id', $request->category_id);
        //         }

        //         # 公開状態
        //         switch ( $request->published_status ) {
        //             case 'published'://公開中
        //                 $query->where('published_at','<>',null)->where('published_at','<=',now());
        //                 break;

        //             case 'reserv_publish'://公開予約
        //                 $query->where('published_at','>',now());
        //                 break;

        //             case 'an_publish'://未公開
        //                 $query->where('published_at',null);
        //                 break;

        //             case 'sold_out'://売り切れ
        //                 $query->where('count',0);
        //                 break;

        //             default:
        //                 # code...
        //                 break;
        //         }


        //         # 還元ポイント順
        //         if( $request->order_points_redemption ){
        //             $query->orderBy('points_redemption', $request->order_points_redemption);
        //         }


        //         # 販売価格順
        //         if( $request->order_price ){
        //             $query->orderBy('price', $request->order_price);
        //         }

        //         # 在庫順
        //         if( $request->order_count ){
        //             $query->orderBy('count', $request->order_count);
        //         }


        //         # 並び替え(order)
        //         switch ($request->order)
        //         {
        //             case 'desc_price': /*高い順*/
        //                 $query->orderByDesc('price');
        //                 break;

        //             case 'asc_price': /*安い順*/
        //                 $query->orderBy('price');
        //                 break;

        //             case 'asc_count': /*在庫が少ない順*/
        //                 $query->orderBy('count');
        //                 break;
        //         }


        //         # 登録が新しい順
        //         $query->orderByDesc('published_at')->orderByDesc('created_at');

        //         # リレーション
        //         $query->with('prize','category');


        //     return $query;
        // }



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
