<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  チケット交換履歴　モデル
| =============================================
*/
class TicketHistory extends Model
{
    use HasFactory;

    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'user_id', //ユーザーID
        'value',     //チケット数
        'reason_id', //入出理由ID
        'store_id',  //商品購入ID
        'point_history_id',//ポイント収支履歴リレーション
    ];




    /**
     * チケットの入出理由　一覧
     *
     * @return Array　
     */
    public static function reasons()
    {
        return [
            //チケット加算
            11 => 'チケット購入',

            14 => '特別付与',
            15 => '会員ランクボーナス',
            16 => 'ポイント購入時プレゼント',

            //チケット減算
            22 => '商品のチケット交換',


            // サブスクプラン(テスト)
            201 => 'テスト月額 3,000円(税込)プラン 契約申請',
            202 => 'テスト月額 3,000円(税込)プラン 契約更新',
            203 => 'テスト月額 3,000円(税込)プラン 契約解除申請',

            211 => 'テスト日額 100円(税込)プラン 契約申請',
            212 => 'テスト日額 100円(税込)プラン 契約更新',
            213 => 'テスト日額 100円(税込)プラン 契約解除申請',

        ];
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
            return $this->belongsTo(User::class);
        }


        /**
         * PointHistoryモデル リレーション
         * @return \App\Models\PointHistory
        */
        public function point_history(){
            return $this->belongsTo(PointHistory::class);
        }


        /**
         * Storeモデル リレーション
         * @return \App\Models\Store
        */
        public function store(){
            return $this->belongsTo(Store::class);
        }


        /**
         * UserPrizeモデル リレーション
         * @return \App\Models\UserPrize
        */
        public function user_prizes()
        {
            return $this->hasMany(UserPrize::class,'ticket_history_id')
            ->orderByDesc('point')
            ->orderByDesc('prize_id')
            ;
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ポイント入出理由 reason
         * @return String
        */
        public function getReasonAttribute()
        {
            $reasons = $this->reasons();

            return isset( $reasons[ $this->reason_id ] )
            ? $reasons[ $this->reason_id ]
            : 'その他' ;
        }

}
