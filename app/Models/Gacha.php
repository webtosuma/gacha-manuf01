<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ガチャ　モデル rank
| =============================================
*/
class Gacha extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'category_id',  //リレーション

        'name',  //名前
        'image', //イメージ画像
        'one_play_point', //1回PLAYポイント数
        'published_at',//公開設定(利用しない->非公開*消さない)
        'key',   //認証キー
        'is_day_once' //一日一回のガチャか
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaFactory::new();
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
         * GachaDiscriptionモデル リレーション
         * @return \App\Models\GachaDiscription
        */
        public function discriptions()
        {
            return $this->hasMany(GachaDiscription::class,'gacha_id');
        }


        /**
         * GachaPrizeモデル リレーション
         * @return \App\Models\GachaPrize
        */
        public function g_prizes()
        {
            return $this->hasMany(GachaPrize::class,'gacha_id')
            ->orderBy('gacha_rank_id','asc'); //ランク順
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
         * 商品・総数 max_count
         * @return String
        */
        public function getMaxCountAttribute()
        {
            return $this->g_prizes->sum('max_count');
        }


        /**
         * 商品・残り数 remaining_count
         * @return String
        */
        public function getRemainingCountAttribute()
        {
            return $this->g_prizes->sum('remaining_count');
        }


        /**
         * 商品・残り割合 remaining_ratio
         * @return String
        */
        public function getRemainingRatioAttribute()
        {
            $max       = $this->max_count;
            $remaining = $this->remaining_count;
            return $max>0 ? ( ($remaining/$max) * 100 ) : 0 ;
        }


        /**
         * ガチャ　プレイ数 played_count
         * @return String
        */
        public function getPlayedCountAttribute()
        {
            $max       = $this->max_count;
            $remaining = $this->remaining_count;
            return $max - $remaining;
        }
    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    // */
    //     /**
    //      * RankA 景品
    //      * @return String
    //     */
    //     public function getRankAPrizesAttribute()
    //     {
    //         return GachaPrize::where('gacha_id',$this->id)
    //         ->where('gacha_rank_id',101)
    //         ->get();
    //     }
    //     /**
    //      * RankB 景品
    //      * @return String
    //     */
    //     public function getRankBPrizesAttribute()
    //     {
    //         return GachaPrize::where('gacha_id',$this->id)
    //         ->where('gacha_rank_id',102)
    //         ->get();
    //     }
    //     /**
    //      * RankC 景品
    //      * @return String
    //     */
    //     public function getRankCPrizesAttribute()
    //     {
    //         return GachaPrize::where('gacha_id',$this->id)
    //         ->where('gacha_rank_id',103)
    //         ->get();
    //     }
    //     /**
    //      * RankD 景品
    //      * @return String
    //     */
    //     public function getRankDPrizesAttribute()
    //     {
    //         return GachaPrize::where('gacha_id',$this->id)
    //         ->where('gacha_rank_id',104)
    //         ->get();
    //     }

}
