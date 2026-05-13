<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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
        'code',    //コード
        'count',   //利用回数
        'one_fee', //1回の料金

        'user_id',
        'machine_id',
        'history_id',      //購入履歴

        /* 決済完了後 */
        'gacha_history_id',//ガチャ履歴   
        'shipped_id',      //発送情報
    ];


    // 合計料金
    protected $appends = [
        'sum_fee',
    ];
    


    /* 重複しないコードの生成 */
    public static function CreateCode()
    {
        $code = ''; $n =8;
        while ( !$code ) {
            $str = 'pur_item_'.Str::random($n);
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
            return $this->belongsTo(
                ManufPurchaseHistory::class, 'history_id'
            )->withTrashed();//削除済みも含む
        }


        /**
         * 発送情報
         */
        public function shipped()
        {
            return $this->belongsTo(
                UserShipped::class,'shipped_id'
            )->withTrashed();//削除済みも含む

        }


        /**
         * ガチャ履歴
         */
        public function gacha_history()
        {
            return $this->belongsTo(
                UserGachaHistory::class,'gacha_history_id'
            )->withTrashed();//削除済みも含む

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