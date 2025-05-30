<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  クーポン履歴　テーブル
| =============================================
*/
class CouponHistory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id'         ,//ユーザーリレーション
        'coupon_id'       ,//クーポンリレーション
        'point_history_id',//ポイント履歴(ポイント交換の時)
        'user_prize_id'   ,//ガチャ商品ID(商品交換の時)
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\CouponHistoryFactory::new();
    }

    /** アクセサーをJSONに含める */
    protected $appends = [
        'image_path',           //画像ファイルパス
        'discription_text',     //提供物の説明文
        'service',              //サービス(商品orポイント)
        // 'expiration_at_format', //有効期限フォーマット
        // 'published_at_format',  //公開日時フォーマット
        // 'published_state',      //公開状態(0:非公開 1:公開 2:公開予約)
        // 'remaining_count',      //残り回数
        // 'admin_remaining_count',//残り回数(Admin 先着用)

        // 'is_published',         //公開中かどうか
        // 'is_expiration_done',   //有効期限が切れているか否か

        // 'r_edit',
        // 'r_destroy',
    ];

    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * USERモデル リレーション
         * @return \App\Models\User
        */
        public function user(){
            return $this->belongsTo(User::class)
            ->withTrashed();//削除済みも含む
        }


        /**
         * Couponモデル リレーション
         * @return \App\Models\Coupon
        */
        public function coupon(){
            return $this->belongsTo(Coupon::class)
            ->withTrashed();//削除済みも含む
        }


        /**
         * PointHistoryモデル リレーション
         * @return \App\Models\PointHistory
        */
        public function point_history(){
            return $this->belongsTo(PointHistory::class)
            ->withTrashed();//削除済みも含む
        }


        /**
         * UserPrizeモデル リレーション
         * @return \App\Models\UserPrize
        */
        public function user_prize()
        {
            return $this->belongsTo(UserPrize::class)
            ->withTrashed();//削除済みも含む
        }




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->user_prize  ? $this->user_prize->prize->image_path : Coupon::pointImage();
        }


        /**
         * サービス(商品orポイント) service
         * @return String
         */
        public function getServiceAttribute()
        {
            return $this->user_prize ? 'prize' : 'point' ;
        }


        /**
         * 提供物の説明文 discription_text
         * @return String
         */
        public function getDiscriptionTextAttribute()
        {
            return $this->user_prize
            ? "『{$this->user_prize ->prize->name}』をプレゼント。"
            : "『{$this->point_history->value}pt』をプレゼント。";
        }


        /**
         * 作成日フォーマット crated_at_format
         * @return String
        */
        public function getCreatedAtFormatAttribute()
        {
            return $this->created_at ? $this->created_at->format('Y年m月d日 H:i') : null ;
        }



}
