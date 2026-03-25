<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        'code',         //認証コード

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


    /* 重複しないコードの生成 */
    public static function CreateCode()
    {
        $code = ''; $n =12;
        while ( !$code ) {
            $str = Str::random($n);
            $model = self::where('code', $str )->first();//重複チェック
            $code = !$model ? $str : '';
            $n ++;
        }
        return $code;
    }


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


        /**
         * [画像パス]ガチャマシーンHead img_path_card_head
         * @return String
        */
        public function getImgPathCardHeadAttribute()
        {

            $text_model = new Text();

            return $text_model->gacha_settings_card_image
            ? $text_model->gacha_settings_card_image_head : null;
        }



        /**
         * [画像パス]ガチャマシーンBody img_path_card_body
         * @return String
        */
        public function getImgPathCardBodyAttribute()
        {

            $text_model = new Text();

            return $text_model->gacha_settings_card_image
            ? $text_model->gacha_settings_card_image_body : null;
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

    /*
    |--------------------------------------------------------------------------
    | アクセサー 公開・日時
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
            return $this->published_status===1;
        }



        /**
         * 公開判定 published_status
         *
         * @return Int
         *   1 : 公開中
         *   2 : 公開予約中
         *   0 : 非公開
         */
        public function getPublishedStatusAttribute(): int
        {
            $now = now();

            # 開始・終了　日時
            $start = $this->published_start_at;
            $end   = $this->published_end_at;


            # start_at,end_at があって、end_atの方が小さい値になってしまっているとき
            if ($start && $end && $start > $end) { return (Int) 0; }

            # 未入力
            if (!$start && !$end) { return (Int) 0; }

            # startが未入力
            if (!$start) { return (Int) 0; }

            # end_at があって、すでに終わっている場合 → 0
            if ($end && $now > $end) { return (Int) 0; }

            # start_at があって、まだ始まっていない場合 → 2
            if ($start && $now < $start) { return (Int) 2; }


            # ここまで来たら有効期間内（または片方nullで条件を満たす）
            return (Int) 1;
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティング]ガチャタイトル詳細 r_show
         * @return String
        */
        public function getRShowAttribute()
        {
            return route('manuf.gacha_title',[
                'category_code' => $this->category->code_name,
                'title_code'    => $this->code,
            ]);
        }


    /* ~ */


}
