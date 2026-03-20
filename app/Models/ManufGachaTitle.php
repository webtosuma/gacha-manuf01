<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

        'gacha_category_id',

        /* 基本情報 */
        'name',         //名称
        'image_samune', //サムネ画像
        'description',  //説明文
        'price',        //価格(税込み)

        /* 日時系 */
        'estimated_shipping_at',//発送予定日時
        'sales_start_at'       ,//販売開始日時
        'sales_end_at'         ,//販売終了日時
        'published_start_at'   ,//公開開始日時
        'published_end_at'     ,//公開終了日時

        /* 詳細情報 */
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

    ];



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
            return $this->belongsTo(GachaCategory::class, 'category_id')
            ->withTrashed();
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */


}
