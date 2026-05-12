<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  Manufacturer用　購入アイテム モデル
| =============================================
*/
class ManufPurchaseItem extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'machine_id',
        'history_id',
        'count',
        'one_fee',
    ];


    // 合計料金
    protected $appends = [
        'sum_fee',
    ];
    

    
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
         * ガチャマシーン
         */
        public function machine()
        {
            return $this->belongsTo(ManufGachaTitleMachine::class)        
            ->withTrashed();//削除済みも含む
        }


        /**
         * 購入履歴
         */
        public function history()
        {
            return $this->belongsTo(ManufPurchaseHistory::class, 'history_id')
            ->withTrashed();//削除済みも含む
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    */


        /**
         * countに応じた合計利用料金
         */
        public function getSumFeeAttribute(): int
        {
            return (int)$this->count * (int)$this->one_fee;
        }


}