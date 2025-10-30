<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  買取表　モデル
| =============================================
*/
class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'prize_id',      //ガチャ商品ID
        'price',         //買取価格
        'published_at',  //公開日
        'is_recommend',  //お勧め中か否か
        'category_id',//カテゴリーID
    ];



    protected $casts = [
        'published_at' => 'datetime',//公開日
    ];



    /** アクセサーをJSONに含める */
    protected $appends = [
        'is_published', //公開中かどうか
        'count',        //査定用数量
        'category_name',//カテゴリー名
        'category_is_published',//カテゴリー公開状態
    ];



    /**
     * 並び替え選択肢 orders
     * @return Array
    */
    public static function orders()
    {
        return [
            ['key'=>'desc_publised',         'label'=>'新しい順'],
            ['key'=>'desc_purchased_count',  'label'=>'人気順'],
            ['key'=>'desc_price',            'label'=>'高い順'],
            ['key'=>'asc_price',             'label'=>'安い順'],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * PurchaseCategoryモデル リレーション
         * @return \App\Models\PurchaseCategory
        */
        public function category(){
            return $this->belongsTo(PurchaseCategory::class, 'category_id');
        }


        /**
         * Prizeモデル リレーション //削除ガチャ用商品のデータは含まない
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class);
            // ->withTrashed();//削除済みも含む
        }




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 公開中かどうか is_published
         * @return Boolean
        */
        public function getIsPublishedAttribute()
        {
            return $this->published_at && $this->published_at <= now()->format('Y-m-d H:i:s') ;
        }


        /**
         * 査定用数量　count
         * @return Boolean
        */
        public function getCountAttribute(){ return 1; }


        /**
         * カテゴリー名 category_name
         * @return Boolean
        */
        public function getCategoryNameAttribute()
        {
            return $this->category ? $this->category->name : null ;
        }


        /**
         * カテゴリー公開状態 category_is_published
         * @return Boolean
        */
        public function getCategoryIsPublishedAttribute()
        {
            return $this->category ? $this->category->is_published : false ;
        }


    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ユーザー表示用スコープ ->forUserPublished()
        */
        public function scopeForUserPublished($query)
        {
            # カテゴリー公開中
            $query->whereHas('category', function ($query){
                $query->where('is_published' , true );
            });

            # 商品公開中
            $query->where('published_at','<>',null)->where('published_at','<=',now());//公開中
        }



        /**
         * 検索スコープ(user admin兼用) ->searchQuery($request)
        */
        public function scopeSearchQuery($query,$request)
        {

            # 商品とのリレーションがあること
            $query->has('prize.category');


            # ID絞り込み(文字列->配列)
            if( $request->ids ){
                $query->whereIn( 'id', explode(',',$request->ids) );
            }


            #　キーワード検索
            if( $request->keyword )
            {
                $query->whereHas('prize', function ($query) use ($request)
                {
                    ## prizeモデル内の処理
                    $query->where('name', 'like', '%' . $request->keyword. '%');

                });
            }



            # 買取カテゴリーの選択
            if(  $request->category_id==999 )
            {
                $query->where('category_id' , null );//未登録のみ
            }
            elseif(  $request->category_id )
            {
                $query->where('category_id' , $request->category_id );
            }


            # ガチャ商品カテゴリーの選択
            if($request->gacha_category_id)
            {
                $query->whereHas('prize', function ($query) use ($request)
                {
                    ## prizeモデル内の処理
                    $query->where('category_id' , $request->gacha_category_id );
                });
            }


            # 公開状態
            switch ( $request->published_status ) {
                case 'published'://公開中
                    $query->where('published_at','<>',null)->where('published_at','<=',now());
                    break;

                case 'reserv_publish'://公開予約
                    $query->where('published_at','>',now());
                    break;

                case 'an_publish'://未公開
                    $query->where('published_at',null);
                    break;

                case 'sold_out'://売り切れ
                    $query->where('count',0);
                    break;

                default:
                    # code...
                    break;
            }

            # 販売価格順
            if( $request->order_price ){
                $query->orderBy('price', $request->order_price);
            }


            # 並び替え(order)
            switch ($request->order)
            {
                // case 'desc_purchased_count': /*人気順*/
                //     $query->orderByDesc('purchased_count');
                //     break;

                case 'desc_price': /*高い順*/
                    $query->orderByDesc('price');
                    break;

                case 'asc_price': /*安い順*/
                    $query->orderBy('price');
                    break;

                case 'desc_points_redemption': /*還元ポイントが多い順*/
                    $query->orderByDesc('points_redemption');
                    break;

                case 'asc_count': /*在庫が少ない順*/
                    $query->orderBy('count');
                    break;
            }


            # 登録が新しい順
            $query->orderByDesc('published_at')->orderByDesc('created_at');

            # リレーション
            $query->with('prize.category');

        }





    /* ~ */
}
