<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  fincode 仮注文 モデル
| =============================================
*/
class FincodePaymentOrder extends Model
{
    use HasFactory;

    public $timestamps = true;

    // protected $table = 'fincode_payment_orders';

    protected $fillable = [
        'user_id',                // ユーザー
        'point_sail_id',          // 購入対象ポイント
        'fincode_transaction_id', //fincodeのtransaction_id
        'status',                 //ステータス
        /**
         * pending:   決済待ち*
         * completed: 決済完了
         * failed:    失敗
         */
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
            ->withTrashed(); // withTrashed() メソッドを追加
        }

        /**
         * PointSailモデル リレーション
         * @return \App\Models\PointSail
        */

        public function pointSail()
        {
            return $this->belongsTo(PointSail::class)
            ->withTrashed(); // withTrashed() メソッドを追加
        }



    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

        public function isCompleted(): bool
        {
            return $this->status === 'completed';
        }

        public function isPending(): bool
        {
            return $this->status === 'pending';
        }

}
