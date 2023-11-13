<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ガチャ　モデル
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
        'ten_play_point', //10回PLAYポイント数
        'published_at',//公開設定(利用しない->非公開*消さない)
        'key',   //認証キー
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
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /** 画像なしの時の画像 */
        public static function noImage(){ return asset( 'storage/'.'site/image/bg04.jpg' );}

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            // return Storage::exists($this->image);
            $noimage_path = '';

            return $this->image && Storage::exists($this->image) ?
            asset( 'storage/'.$this->image ) :  self::noImage();
        }
}
