<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  PayPay注文情報(ポイント購入待機)　モデル
| =============================================
*/
class PayPayOrder extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'user_id',                   //ユーザーID
        'point_sail_id',             //販売ポイントID
        'point_history_id',          //ポイント履歴ID(決済完了後に保存)
        'paypay_merchant_payment_id',//PayPay決済ID
    ];



    /* 重複しないPayPay決済IDの生成 */
    public static function CreatePaypayMerchantPaymentId()
    {
       $id = null;
        while ( !$id ) {
            $str = 'mpid_' . rand() . time();
            $model = self::where('paypay_merchant_payment_id', $str )->first();//重複チェック
            $id = !$model ? $str : null;
        }
        return $id;
    }



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
            ->withTrashed(); // 削除を含む
        }

        /**
         * PointSailモデル リレーション
         * @return \App\Models\PointSail
        */
        public function point_sail(){
            return $this->belongsTo(PointSail::class)
            ->withTrashed(); // 削除を含む
        }


        /**
         * PointHistoryモデル リレーション
         * @return \App\Models\PointHistory
        */
        public function point_history(){
            return $this->belongsTo(PointHistory::class)
            ->withTrashed(); // 削除を含む
        }
}
