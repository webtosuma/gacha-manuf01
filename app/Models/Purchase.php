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
        'done_at',       //買取停止日
    ];



    protected $casts = [
        'published_at'  => 'datetime',///公開日
        'done_at'       => 'datetime',//買取停止日
    ];



    /** アクセサーをJSONに含める */
    protected $appends = [
        'is_published',//公開中かどうか
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
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class)
            ->withTrashed();//削除済みも含む
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
            # カテゴリーリレーション(API用)
            // $query->with('category');

            # 公開中
            $query->where('published_at','<>',null)->where('published_at','<=',now());//公開中

            # カテゴリーが公開中
            // $query->whereHas('category', function ($query){
            //     $query->where('is_published', 1);
            // });
        }



        /**
         * 検索スコープ(user admin兼用) ->searchQuery($request)
        */
        public function scopeSearchQuery($query,$request)
        {

            #　キーワード検索
            if( $request->keyword )
            {
                $query->whereHas('prize', function ($query) use ($request)
                {
                    ## prizeモデル内の処理
                    $query->where('name', 'like', '%' . $request->keyword. '%');

                });
            }



            # カテゴリーの選択
            if(  $request->category_id )
            {
                $query->whereHas('prize', function ($query) use ($request)
                {
                    ## prizeモデル内の処理
                    $query->where('category_id' , $request->category_id );

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
