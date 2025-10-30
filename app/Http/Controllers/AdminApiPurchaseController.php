<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GachaCategory;
use App\Models\Purchase;
use App\Models\PurchaseCategory;
use App\Models\Prize;
use App\Models\PrizeRank;
use App\Models\StoreItem;//
/*
|--------------------------------------------------------------------------
| Admin 買取表 API　コントローラー
|--------------------------------------------------------------------------
*/
class AdminApiPurchaseController extends Controller
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
        $purchases = Purchase::searchQuery($request)->paginate(20);


        # 買取カテゴリー一覧
        $categories = PurchaseCategory::adminList()->get();

        # ガチャ商品カテゴリー一覧
        $gacha_categories = GachaCategory::adminList()->get();

        # 公開状態選択肢
        $published_statuses = [
            ['key'=>'published',      'label'=> '公開中'  ],
            // ['key'=>'reserv_publish', 'label'=> '公開予約'],
            ['key'=>'an_publish',     'label'=> '未公開'  ],
        ];

        # 並び替え選択肢
        $orders = Purchase::orders();


        $inputs = $request->all();


        return response()->json( compact(
            'purchases','categories','gacha_categories',
            'published_statuses','orders','inputs'
        ) );
    }


        /** 一括処理 */
        public function apiBulk($request)
        {
            $purchases = Purchase::whereIn('id',$request->purchase_ids)->get();

            # 一括削除
            if( $request->bulk ==='delete' ){
                foreach ($purchases as $purchase) { $purchase->delete(); }
            }

            # すべて公開に変更
            if( $request->bulk ==='published_true' ){
                foreach ($purchases as $purchase) { $purchase->update(['published_at'=>now()]); }
            }

            # すべて非公開に変更
            if( $request->bulk ==='published_false' ){
                foreach ($purchases as $purchase) { $purchase->update(['published_at'=>null]); }
            }

            # カテゴリー一括変更(bulk_category_id)
            if( $request->bulk_category_id ){
                foreach ($purchases as $purchase) { $purchase->update(['category_id'=>$request->bulk_category_id]); }
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
        $purchase = Purchase::find($request->id);


        # 入力データの加工
        $inputs = $request->only([
            'price',//販売価格
            'published_at',
            'category_id' ,//買取カテゴリーID
        ]);
        # 公開設定
        $inputs['published_at'] = self::processingPublished( $request, $purchase );


        # DBデータの更新
        $purchase->update($inputs);

        # 操作ログの更新
        AdminLogController::createLog( 'purchase.edit' );


        return response()->json(['purchase'=>$purchase,'requests'=>$inputs]);
    }



        /* 公開設定 */
        public static function processingPublished( $request, $purchase=null )
        {
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

            return $published_at;
        }





    /**
     * ガチャ商品一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function prize(Request $request)
    {
        # 商品情報の取得
        $prizes = self::getPrizes($request);

        # その他のデータ
        $prize_ranks = PrizeRank::all();//評価ランクデータ

        return response()->json( compact('prizes' ,'prize_ranks') );
    }


        /**
         * ガチャ商品情報の取得
         * @return Prizes $prizes
         */
        public static function getPrizes($request)
        {
            $query = Prize::query();

                # キーワード(key_words)から検索
                AdminApiPrizeController::KeyWordSearch($request, $query);


                # カテゴリーの選択
                if(  $request->category_id ){
                    $query->where('category_id', $request->category_id);
                }

                # 並び替え：コードネーム順
                if( $request->order_code ){
                    $query->orderBy('code', $request->order_code);
                }

                # 並び替え：商品名順
                if( $request->order_name ){
                    $query->orderBy('name', $request->order_name);
                }

                # 並び替え：ランク
                if( $request->order_rank_id ){
                    $query->orderBy('rank_id', $request->order_rank_id);
                }

                # 絞り込み：ランク
                if( $request->where_rank_id ){
                    $query->where('rank_id', $request->where_rank_id);
                }

                # 並び替え：ポイント
                if( $request->order_point ){
                    $query->orderBy('point', $request->order_point);
                }

                # 並び替え：更新日
                if( $request->updated_at ){
                    $query->orderBy('updated_at', $request->updated_at);
                }else{
                    $query->orderByDesc('created_at');
                }

                # ポイント最大値
                if( $request->max_point ){
                    $query->where('point','<=', $request->max_point);
                }

                # ポイント最低値
                if( $request->min_point ){
                    $query->where('point','>=', $request->min_point);
                }


                # 指定したIDを含む
                if( $request->ids ){
                    $query->whereIn('id', $request->ids);
                }

                # 指定したIDを除く
                if( $request->not_ids ){
                    $query->whereNotIn('id', $request->not_ids);
                }

                # 登録済み商品を含まない
                $query->doesntHave('purchases');

            $prizes = $query->with('rank')->paginate(10);

            # 画像パスの登録
            foreach ($prizes as $prize) {
                $prize->image_path = $prize->image_path;
                $prize->is_used = $prize->is_used;
            }

            return $prizes;
        }


    /* ~ */
}
