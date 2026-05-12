<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
/*
| =============================================
|  Manufacturer用　購入履歴 モデル
| =============================================
*/

class ManufPurchaseHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $fillable = [
        'code',        //履歴コード
        'user_id',     //
        'address_id',  //アドレスID(保存用)
        'shipped_fee', //発送料金
        'status',      //状態 (pending:購入待ち paid:支払い済み cancel:キャンセル')
        
        'stripe_checkout_session_id',//Stripe Checkout Session ID
        'shipped_id',  //発送情報
        'paid_at',     //支払い完了日時
    ];


    /*　 キャスト */
    protected $casts = [
        'paid_at' => 'datetime',
    ];


    protected $appends = [
        'total_fee',
        'sub_total_fee',
    ];




    /* 重複しないコードの生成 */
    public static function CreateCode()
    {
        $code = ''; $n =8;
        while ( !$code ) {
            $str = 'pur_h_'.Str::random($n);
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
    */

        /**
         * ユーザー
         */
        public function user()
        {
            return $this->belongsTo(User::class)
            ->withTrashed();//削除済みも含む
        }


        /**
         * 購入アイテム一覧
         */
        public function items()
        {
            return $this->hasMany(
                ManufPurchaseItem::class,'history_id'
            );
        }


        /**
         * 発送情報
         */
        public function shipped()
        {
            return $this->belongsTo(
                UserShipped::class,'shipped_id'
            )           
            ->withTrashed();//削除済みも含む

        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    */


        /**
         * 購入アイテム合計金額
         */
        public function getSubTotalFeeAttribute(): int
        {
            return (int)$this->items->sum(function ($item) {
                return $item->sum_fee;
            });
        }


        /**
         * 購入アイテム + 発送料金
         */
        public function getTotalFeeAttribute(): int
        {
            return (int)$this->sub_total_fee + (int)$this->shipped_fee;
        }


}