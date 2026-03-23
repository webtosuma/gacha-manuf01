<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  Manufacturer用　ガチャタイトル モデル
| =============================================
*/
class ManufGachaTitle extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    protected $fillable = [

        /* 基本情報 */
        'category_id',
        'name',         //名称
        'image_samune', //サムネ画像
        'price',        //価格(税込み)

        /* 日時系 */
        'estimated_shipping_at',//発送予定日時
        'sales_start_at'       ,//販売開始日時
        'sales_end_at'         ,//販売終了日時
        'published_start_at'   ,//公開開始日時
        'published_end_at'     ,//公開終了日時

        /* 詳細情報 */
        'description',    //説明文
        'set_contents',   //セット内容
        'prize_size',     //商品サイズ
        'prize_materials',//商品素材
        'age_range',      //対象年齢
        'copy_right',     //コピーライト

    ];

    protected $casts = [
        'estimated_shipping_at' => 'datetime',//発送予定日時
        'sales_start_at'        => 'datetime',//販売開始日時
        'sales_end_at'          => 'datetime',//販売終了日時
        'published_start_at'    => 'datetime',//公開開始日時
        'published_end_at'      => 'datetime',//公開終了日時
    ];


    /**
     * アクセサーをJSONに含める
     */
    protected $appends = [
        'image_samune_path',//画像ファイルパス
        'ratio',            //画像比率　
        'description_text', //説明文
        'set_contents_text',//セット内容

    ];



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /** GachaCategoryモデル リレーション */
        public function category(){
            return $this->belongsTo(GachaCategory::class, 'category_id')
            ->withTrashed();
        }


        /** ManufGachaTitlePrizeモデル リレーション */
        public function title_prizes(){
            return $this->hasMany(ManufGachaTitlePrize::class, 'manuf_gacha_title_id');
        }


        /** ManufGachaTitleMachineモデル リレーション */
        public function machines(){
            return $this->hasMany(ManufGachaTitleMachine::class, 'manuf_gacha_title_id');
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
         * 画像ファイルパス image_samune_path
         * @return String
        */
        public function getImageSamunePathAttribute()
        {
            return $this->image_samune && Storage::exists($this->image_samune) ?
            asset( 'storage/'.$this->image_samune ) :  self::noImage();
        }


        /**
         * 画像比率　ratio
         * @return String
        */
        public function getRatioAttribute()
        {
            return config('app.gacha_card_ratio');
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー ストレージ保存された文章
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ストレージ保存された文章（説明文） description_text
         * @return String
         */
        public function getDescriptionTextAttribute()
        {
            # パスから改行を取り除く
            $text = $this->description;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }


        /**
         * ストレージ保存された文章（セット内容） set_contents_text
         * @return String
         */
        public function getSetContentsTextAttribute()
        {
            # パスから改行を取り除く
            $text = $this->set_contents;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }


    /* ~ */


}
