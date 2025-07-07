<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminApiPrizeController;
use App\Http\Controllers\AdminLogController;//操作ログ
use App\Http\Controllers\Method;//コントローラーメソッド

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\StoreItem;
use App\Models\Prize;
use App\Models\PrizeRank;
/*
| =============================================
|  ストアーAdmin ストアー商品・ガチャ用商品 コントローラー
| =============================================
*/
class AdminStoreItemPrizeController extends Controller
{
    /**
     * ガチャ用商品を追加
     *
     * @param String $category_code
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        # カテゴリーコードの認証
        $category_id = $request->category_id;


        return view('store_admin.store_item.prize.create',compact(
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
            $store_item = new StoreItem([
                'category_id' => $prize->category_id,//カテゴリー　リレーション
                'prize_id'    => $prize->id,         //ガチャ用商品リレーション
                'item_name'   => $prize->name,//アイテム名
            ]);
            $store_item->save();
        }


        # 操作ログの更新
        AdminLogController::createLog( 'store_item.prize.create', $store_item->id );

        $request->session()->regenerateToken();// 二重送信防止


        # 返信メッセージ
        return redirect()->route('admin.store_item')
        ->with(['alert-success'=>'ガチャ用商品をストアー商品として登録しました']);
    }



    /**
     * 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function api_index(Request $request)
    {
        # 商品情報の取得
        $prizes = self::getPrizes($request);

        # その他のデータ
        $prize_ranks = PrizeRank::all();//評価ランクデータ

        return response()->json( compact('prizes' ,'prize_ranks') );
    }


        /**
         * 商品情報の取得
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

                $query->doesntHave('store_item');//EC商品に登録済みを除く

            $prizes = $query->with('rank')->paginate(10);

            # 画像パスの登録
            foreach ($prizes as $prize) {
                $prize->image_path = $prize->image_path;
                $prize->is_used = $prize->is_used;
            }

            return $prizes;
        }
}
