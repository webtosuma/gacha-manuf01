<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
| =============================================
|  ガチャのカテゴリーグループ　モデル
| =============================================
*/
class GachaCategory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'name',        //名前
        'code_name',   //'コードネーム（ルーティング用）'
        'bg_image' ,   //'背景画像'
        'is_published',//公開(bool)
    ];



    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaCategoryFactory::new();
    }




    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * Gachaモデル リレーション
         * @return \App\Models\Gacha
        */
        public function gachas()
        {
            return $this->hasMany(Gacha::class,'category_id')
            ->orderByDesc('created_at');
        }



        /**
         * 公開中ガチャ published_gachas
         * @return String
        */
        public function getPublishedGachasAttribute()
        {
            return Gacha::where('category_id',$this->id)
            ->where('published_at', '<' ,now() )
            ->get();
        }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /** 画像なしの時の画像 */
        public static function noImage(){ return asset( 'storage/'.'site/image/bg02.jpg' );}

        /**
         * 画像ファイルパス bg_image_path
         * @return String
        */
        public function getBgImagePathAttribute()
        {
            return $this->bg_image && Storage::exists($this->bg_image) ?
            asset( 'storage/'.$this->bg_image ) : self::noImage();
        }

}
