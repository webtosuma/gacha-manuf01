<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  商品　モデル
| =============================================
*/
class Prize extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'category_id',  //リレーション
        'code',   //商品コード
        'name',   //名前
        'image',  //画像
        'rank_id',//ランクID
        'point',  //交換ポイント値
        'point_updated_at', //交換ポイント値更新日時
        'published_at',//公開日時
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\PrizeFactory::new();
    }


    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * GachaCategoryモデル リレーション
         * @return \App\Models\GachaCategory
        */
        public function category(){
            return $this->belongsTo(GachaCategory::class, 'category_id');
        }

        /**
         * PrizeRankモデル リレーション (rank)
         * @return \App\Models\PrizeRank
        */
        public function rank()
        {
            return $this->belongsTo(PrizeRank::class,'rank_id');
        }


        /**
         * GachaPrizeモデル リレーション
         * @return \App\Models\GachaPrize
        */
        public function g_prizes()
        {
            return $this->hasMany(GachaPrize::class,'prize_id'); //ランク順
        }



        /**
         * UserPrizeモデル リレーション
         * @return \App\Models\UserPrize
        */
        public function u_prizes()
        {
            return $this->hasMany(UserPrize::class,'prize_id');
        }
    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /** 画像なしの時の画像 */
        public static function noImage(){ return asset( 'storage/site/image/no_image.jpg' );}

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->image && Storage::exists($this->image) ?
            asset( 'storage/'.$this->image ) :  self::noImage();
        }


        /**
         * 利用中か否か image_path
         * @return String
        */
        public function getIsUsedAttribute()
        {
            $user_prizes_count  = $this->u_prizes->count();
            $gacha_prizes_count = $this->g_prizes->count();

            return $user_prizes_count + $gacha_prizes_count > 0 ;
        }

}
