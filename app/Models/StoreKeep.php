<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  EC 買い物カート　モデル
| =============================================
*/
class StoreKeep extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id'      ,//ユーザー　　　リレーション
        'store_item_id',//販売アイテム　リレーション
        'count'        ,//注文数
        'is_buy_now'   ,//今すぐ購入か否か(カート非表示)　


        /*チェックアウト時、登録 */
        'store_history_id'          ,//販売履歴　　　リレーション
        'done_sum_price'            ,//注文時の合計価格
        'done_sum_points_redemption',//注文時の合計還元ポイント
        'done_store_item_name'      ,//注文時の商品名

        /* 決済完了後登録 */
        'done_at',//決済完了日時
    ];


    /** アクセサーをJSONに含める */
    protected $appends = [
        'done_at_format',       //購入日フォーマット
        'sum_price',            //合計価格(価格×数量)
        'sum_points_redemption',//合計価格(価格×数量)

        'r_api_update', //[ルーティング]API 購入数の変更
        'r_api_destroy',//[ルーティング]API 削除
    ];


    /** 型指定 */
    protected $casts = [
        'done_at'  => 'datetime',//決済完了日時
    ];


    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * USERモデル リレーション
         * @return \App\Models\User
        */
        public function user(){
            return $this->belongsTo(User::class)
            ->withTrashed();//削除済みも含む
        }


        /**
         * StoreItemモデル リレーション
         * @return \App\Models\StoreItem
        */
        public function store_item(){
            return $this->belongsTo(StoreItem::class)
            ->withTrashed();//削除済みも含む
        }


        /**
         * StoreHistoryモデル リレーション
         * @return \App\Models\StoreHistory
        */
        public function store_history(){
            return $this->belongsTo(StoreHistory::class,'store_history_id');
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 購入日フォーマット done_at_format
         * @return Integer
        */
        public function getDoneAtFormatAttribute()
        {
            return $this->done_at ? $this->done_at->format('購入日：Y年m月d日'): null;
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー 合計値算出
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 合計価格(価格×数量) sum_price
         * @return Integer
        */
        public function getSumPriceAttribute()
        {
            return $this->done_at
            ? $this->done_sum_price //決済完了時
            : $this->store_item->price * $this->count;//未完了時
        }

        /**
         * 合計還元ポイント(還元ポイント×数量) sum_points_redemption
         * @return Integer
        */
        public function getSumPointsRedemptionAttribute()
        {
            return $this->done_at
            ? $this->done_sum_points_redemption //決済未完了時
            : $this->store_item->points_redemption * $this->count;//未完了時
        }



    /*
    |--------------------------------------------------------------------------
    | ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティング]API 購入数の変更 r_api_update
         * @return Integer
        */
        public function getRApiUpdateAttribute()
        { return route('store.keep.api.update', $this->id); }


        /**
         * [ルーティング]API 削除 r_api_destroy
         * @return Integer
        */
        public function getRApiDestroyAttribute()
        { return route('store.keep.api.destroy', $this->id); }



    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ユーザー表示用スコープ ->forUserPublished($request)
         * (買い物カート・購入確認)
        */
        public function scopeForUserPublished($query,$request=null)
        {
            # ユーザー情報
            $user = Auth::user();

            # ログインユーザーのみ
            $query->where('user_id',$user->id);

            # 購入済みのカート商品を除く
            $query->where('done_at',null);


            # 指定のIDのみ(購入確認ページ)
            $ids = $request ? $request->ids : null;
            if($ids){
                $query->find($ids);
            }
            # 今すぐ購入の商品は除く(購入確認ページでは利用しない)
            else
            {
                $query->where('is_buy_now',0);
            }


            # リレーション
            $query->with('store_item');

            # 登録が新しい順
            $query->orderByDesc('created_at');
        }


        /**
         * キーワード(key_words)から検索するメソッド ->keywordSearch($request)
         *
         * @param \Illuminate\Http\Request $req
         * @param App\Models\Recruit::query $query
         * @return App\Models\Recruit::query
         */
        public static function scopeKeywordSearch( $query, $req )
        {
            #検索パラメータが存在するか
            if( !$req->has('keyword') ){ return; }

            #文字列を配列へ変換
            $keywords = self::ArrayConvertString( $req->keyword );

            #検索条件の絞り込み(全ての条件に該当するデータを検索:and)
            foreach ($keywords as $keyword) {

                $query->where(function($q) use ($keyword) {

                    $q->where('done_store_item_name', 'like', '%' . $keyword . '%');

                });

            }
        }

        /**
        * 文字列を配列へ変換
        *
        * @param  String $string
        * @return Array
        */
       public static function ArrayConvertString($string)
       {
           $string = str_replace('　',' ',$string);
           $array  = explode(' ',$string);
           return $array;
       }


}
