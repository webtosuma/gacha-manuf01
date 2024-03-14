<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\Prize;
use App\Models\PrizeRank;
use App\Models\UserPrize;
use App\Models\TicketHistory;
/*
| =============================================
|  Admin API チケット ストアー コントローラー
| =============================================
*/
class AdminApiTicketStoreController extends Controller
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
        $stores = self::Search($request);

        return response()->json( compact('stores' ) );
    }


        /**
         * 交換商品情報の取得・検索
         * @return Store $prizes
         */
        public static function Search($request)
        {
            # 商品情報の取得
            $query = Store::query();

                # カテゴリーの選択
                if(  $request->category_id ){
                    $query->where('category_id', $request->category_id);
                }

                #　キーワード検索
                if( $request->key_words ){
                    $prize_ids = self::KeyWordPrizeId($request);// キーワードに該当する商品ID($prize_ids)
                    $query->whereIn('prize_id', $prize_ids);
                }

                # 公開順
                if( $request->order_published_at ){
                    $query->orderBy('published_at', $request->order_published_at);
                }

                # 交換チケット順
                if( $request->order_ticket_count ){
                    $query->orderBy('ticket_count', $request->order_ticket_count);
                }

                # 交換ポイント順
                if( $request->order_point_count ){
                    $query->orderBy('point_count', $request->order_point_count);
                }

                # 在庫順
                if( $request->order_count ){
                    $query->orderBy('count', $request->order_count);
                }

                # ポイント最大値
                if( $request->max_ticket ){
                    $query->where('ticket_count','<=', $request->max_ticket);
                }

                # ポイント最低値
                if( $request->min_ticket ){
                    $query->where('ticket_count','>=', $request->min_ticket);
                }


                # 登録が新しい順
                $query->orderByDesc('created_at');

            $stores = $query->with('prize')->paginate(100);


            # 画像パスの登録
            foreach ($stores as $store) {
                $store->prize->image_path = $store->prize->image_path;
                $store->is_published = $store->published_at!=null ;
            }

            return $stores;
        }


        /**
         * 商品IDをキーワード(key_words)から検索
         *
         * @param \Illuminate\Http\Request $req
         * @param App\Models\Recruit::query $query
         * @return App\Models\Recruit::query
         */
        public static function KeyWordPrizeId($request)
        {
            $query = Prize::query();
            AdminApiPrizeController::KeyWordSearch($request, $query);
            $prize_ids = $query->get()->pluck('id')->toArray();

            return $prize_ids;
        }


    //


    /**
     * 新規作成
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        # 新規登録する商品ID(文字列->配列)
        $prize_ids = $request->ids ? $request->ids : [];

        $new_stores = [];//新規作成する交換商品
        foreach ($prize_ids as $prize_id)
        {
            $prize = Prize::find($prize_id);
            $store = new Store([
                'prize_id'    => $prize_id,//商品ID
                'category_id' => $prize->category_id,//カテゴリーID
                'user_id'     => 1,//出品者ID

                'ticket_count'=> 10,//交換チケット数
                'point_count' => $prize->point,//交換ポイント数
                'count'       => 0,//在庫数
            ]);
            $store->save();
            $new_stores[] = $store;
        }

        return response()->json( compact( 'new_stores','request' ) );
    }




    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param Store $store
     * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Store $store)
    {
        # 更新
        $inputs = [
            'ticket_count' => $this->PositiveNumber($request, $store, 'ticket_count'),//交換チケット数
            'point_count'  => $this->PositiveNumber($request, $store, 'point_count'), //交換ポイント数
            'count'        => $this->PositiveNumber($request, $store, 'count'),       //在庫数
            'published_at' => !$request->is_published ? null : (//公開設定(非公開)
                !$store->published_at ? now() //非公開->公開
                : $store->published_at
            ),
        ];

        # 公開日そのまま（公開->公開）
        // $inputs['published_at'] = $request->published_at == $store->published_at
        // ? $store->published_at : $inputs['published_at'];

        # 更新
        $store->update( $inputs);


        $request_all = $request->all();
        return response()->json( compact( 'request_all','store' ) );
    }
    /** 整数を返す */
    public static function PositiveNumber($request, $store, $column){
        return is_numeric( $request[$column] ) && $request[$column] >= 0 //整数 && 0以上
        ? $request[$column] : $store[$column];
    }



    /**
     * 削除
     *
     * @param \Illuminate\Http\Request $request
     * @param Store $store
     * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Store $store)
    {
        $store->delete();

        $message = 'Delete OK!';
        $request_all = $request->all;
        return response()->json( compact( 'message','request_all' ) );
    }
}
