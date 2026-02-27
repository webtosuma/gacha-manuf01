<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        'category_id',      //リレーション
        'code',             //商品コード
        'name',             //名前
        'image',            //画像
        'rank_id',          //ランクID
        'point',            //交換ポイント値
        'point_updated_at', //交換ポイント値更新日時
        'published_at',     //公開日時
        'discription',      //説明文(2025/06/12追加)
        'ticket',           //交換チケット値(2025/07/09追加)
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


    /* 重複しないコードの生成 */
    public static function CreateCode()
    {
        $code = ''; $n =8;
        while ( !$code ) {
            $str = Str::random($n);
            $model = self::where('code', $str )->first();//重複チェック
            $code = !$model ? $str : '';
            $n ++;
        }
        return $code;
    }


    /** アクセサーをJSONに含める */
    protected $appends = [
        'image_path',            //画像ファイルパス
        'discription_icon_path', //説明文モーダルアイコン
        'discription_text',      //説明文
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
            return $this->hasMany(GachaPrize::class,'prize_id')->whereHas('gacha');
        }



        /**
         * UserPrizeモデル リレーション
         * @return \App\Models\UserPrize
        */
        public function u_prizes()
        {
            return $this->hasMany(UserPrize::class,'prize_id');
        }



        /**
         * StoreItemモデル リレーション
         * @return \App\Models\StoreItem
        */
        public function store_item()
        {
            return $this->hasOne(StoreItem::class,'prize_id');
        }



        /**
         * Purchaseモデル リレーション //買取表
         * @return \App\Models\Purchase
        */
        public function purchases()
        {
            return $this->hasMany(Purchase::class,'prize_id');
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
            $url = $this->image && Storage::exists($this->image) ?
            asset( 'storage/'.$this->image ) :  self::noImage();

            return $url.'?v='.config('app.version');//バージョン管理
        }


        /**
         * 利用中か否か is_used
         * @return String
        */
        public function getIsUsedAttribute()
        {

            $user_prizes_count  = $this->u_prize_first;
            $gacha_prizes_count = $this->g_prize_first;

            return $user_prizes_count or $gacha_prizes_count ;
        }
        /** GachaPrizeモデル リレーション (1ケ)*/
        public function g_prize_first(){
            return $this->hasOne(GachaPrize::class,'prize_id')->orderByDesc('created_at');
        }
        /** UserPrizeモデル リレーション (1ケ)*/
        public function u_prize_first(){
            return $this->hasOne(UserPrize::class,'prize_id')->orderByDesc('created_at');
        }



        /**
         * ストレージ保存された文章（説明文） discription_text
         * @return String
         */
        public function getDiscriptionTextAttribute()
        {
            // パスから改行を取り除く
            $text = $this->discription;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }


        /**
         * 説明文モーダルアイコン discription_icon_path
         */
        public static function getDiscriptionIconPathAttribute()
        {
            return asset('storage/site/image/prize_discription.png');
        }

}
