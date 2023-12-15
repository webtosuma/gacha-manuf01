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
        'key',   //認証キー
        'type',  //ガチャの種類
        'meta_discription', //メタタグ用の説明文
        'one_play_point',   //1回PLAYポイント数
        'published_at',     //公開設定(利用しない->非公開*消さない)
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


    /** Carbonオブジェクトとして利用 */
    protected $dates = [
        'published_at',//公開設定(利用しない->非公開*消さない)
    ];


    /** ガチャランク　一覧 */
    public static function types()
    {
        return [
            'nomal'       => '通常',
            'one_time'    => '一回限定',
            'only_oneday' => '一日限定',
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
        /**
         * 公開中かどうか is_published
         * @return String
        */
        public function getIsPublishedAttribute()
        {
            return $this->published_at && $this->published_at < now()->format('Y-m-d H:i:s') ;
        }





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


        /**
         * ランクの商品ポイント合計 total_point
         * @return String
        */
        public function getTotalPointAttribute()
        {
            $g_prizes = $this->g_prizes;
            $point = 0;
            foreach ($g_prizes as $g_prize) {
                $point +=  $g_prize->prize->point/*ポイント数*/ * $g_prize->max_count/*登録数*/;
            }
            return $point;
        }
}
