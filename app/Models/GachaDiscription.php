<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  ガチャの詳細説明情報　モデル
| =============================================
*/
class GachaDiscription extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
       'gacha_id', //ガチャリレーション
       'image',//画像
       'sorce',//説明文
       'rank_id',//ランクID
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaDiscriptionFactory::new();
    }




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        // /** 画像なしの時の画像 */
        // public static function noImage(){ return asset( 'storage/'.'site/image/bg04.jpg' );}

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->image && Storage::exists($this->image) ?
            asset( 'storage/'.$this->image ) :  null;
        }


        /**
         * ストレージ保存された文章（説明文） sorce_text
         * @return String
         */
        public function getSorceTextAttribute()
        {
            // パスから改行を取り除く
            $text = $this->sorce;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }

}
