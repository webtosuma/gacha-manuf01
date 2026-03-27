<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

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


        /** ManufGachaTitleImageモデル リレーション */
        public function title_images(){
            return $this->hasMany(ManufGachaTitleImage::class, 'manuf_gacha_title_id');
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
         * スライド画像 slide_images
         */
        public function getSlideImagesAttribute()
        {
            $titpe_prize_images = ManufGachaTitlePrize::where('manuf_gacha_title_id',$this->id)
            ->get()->pluck('image_path')->toArray();


            return [
                # サムネ画像
                $this->image_samune_path,

                # 商品情報
                ... $titpe_prize_images,
            ];
        }


        /**
         * 紹介画像 discription_images
         */
        public function getDiscriptionImagesAttribute()
        {
            return ManufGachaTitleImage::where('manuf_gacha_title_id',$this->id)
            ->get()->pluck('image_path')->toArray();
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
    | アクセサー 公開日時
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * NEW判定 is_new
         */
        public function getIsNewAttribute()
        {
            return $this->published_start_at
                ? $this->published_start_at->isAfter(now()->copy()->subWeek())
                : false;
        }



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


        /**
         * 公開開始日時テキスト(曜日入り)
         */
        public function getPublishedStartAtTextAttribute(){
            return $this->formatJaDateTime($this->published_start_at);
        }

        /**
         * 公開終了日時テキスト(曜日入り)
         */
        public function getPublishedEndAtTextAttribute(){
            return $this->formatJaDateTime($this->published_end_at);
        }


            /* 日本語日付テキスト変換 */
            private function formatJaDateTime($value)
            {
                if (!$value) return null;

                return Carbon::parse($value)
                    ->locale('ja')
                    ->isoFormat('YY年MM月DD日(ddd) HH:mm');
            }


        //
    /*
    |--------------------------------------------------------------------------
    | アクセサー 発送日時
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * 発送ステータス estimated_shipping_status
         * -1: 販売終了
         *  0: 発送時期未定
         *  1: 発送予定
         *  2: すぐに発送
         */
        public function getEstimatedShippingStatusAttribute()
        {
            $now = now();

            # ① 販売終了
            if ($this->sales_end_at && $this->sales_end_at->lt($now)) {
                return -1;
            }

            # ② 発送時期未定
            if (is_null($this->estimated_shipping_at)) {
                return 0;
            }

            # ③ 発送予定（未来）
            if ($this->estimated_shipping_at->gt($now)) {
                return 1;
            }

            # ④ すぐに発送（今日以前）
            return 2;
        }



        /**
         * 発送予定ラベル estimated_shipping_label
         */
        public function getEstimatedShippingLabelAttribute()
        {
            switch ($this->estimated_shipping_status) {

                # 販売終了
                case -1: return '販売終了'; break;

                # 発送時期未定
                case  0: return '発送時期未定'; break;

                # xx発送予定
                case 1:

                    return $this->estimated_shipping_at
                    ? $this->estimated_shipping_at->format('n') . '月' . (
                        $this->estimated_shipping_at->day <= 10 ? '上旬' :
                        ($this->estimated_shipping_at->day <= 20 ? '中旬' : '下旬')
                    ) . '発送予定'
                    : '発送時期未定' ; break;

                # すぐに発送
                case  2: return 'すぐに発送'; break;

            }


        }



        /**
         * 発送予定ボタンクラス estimated_shipping_label_style
         */
        public function getEstimatedShippingLabelStyleAttribute()
        {
            switch ($this->estimated_shipping_status) {

                # 販売終了
                case -1: return 'btn btn-sm btn-secondary text-white'; break;

                # 発送時期未定
                case  0: return 'btn btn-sm bg-white border '; break;

                # xx発送予定
                case  1: return 'btn btn-sm btn-warning'; break;

                # すぐに発送
                case  2: return 'btn btn-sm btn-success text-white'; break;

            }


        }



        /**
         * 発送予定日時テキスト(曜日入り)　estimated_shipping_at_text
         */
        public function getEstimatedShippingAtTextAttribute(){
            return $this->formatJaDateTime($this->estimated_shipping_at);
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー 販売日時
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 販売開始日時テキスト(曜日入り)
         */
        public function getSalesStartAtTextAttribute(){
            return $this->formatJaDateTime($this->sales_start_at);
        }

        /**
         * 販売終了日時テキスト(曜日入り)
         */
        public function getSalesEndAtTextAttribute(){
            return $this->formatJaDateTime($this->sales_end_at);
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


        /**
         * [ルーティング]カテゴリールート r_category
         */
        public function getRCategoryAttribute()
        {
            return route('manuf.search', [
                'category_id' => $this->category_id,
            ]);
        }




    /* ~ */


}
