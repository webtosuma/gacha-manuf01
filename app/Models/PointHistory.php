<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
| =============================================
|  ポイント収支履歴　モデル
| =============================================
*/
class PointHistory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',   //ユーザー　リレーション
        'value',     //ポイント数
        'price',     //販売価格(税込み)＊ポイント販売時
        'reason_id', //入出理由ID
        'stripe_checkout_session_id'//Stripe チェックアウト処理ID
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\PointHistoryFactory::new();
    }

    /**
     * ポイントの入出理由　一覧
     *
     * @return Array　
     */
    public static function reasons()
    {
        return [
            //ポイント加算
            11 => 'ポイント購入',
            12 => '商品のポイント交換',
            13 => 'キャンペーン付与',
            14 => '特別付与',
            15 => '会員ランクボーナス',

            //ポイント減算
            21 => 'ガチャPLAY',
            22 => '商品発送',

            //キャンペーン
            31 => '紹介キャンペーン：紹介ユーザー',
            32 => '紹介キャンペーン：新規登録ユーザー',
            33 => '初回ポイント購入キャンペーン',


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
         * UserGachaHistoryモデル リレーション
         * @return \App\Models\UserGachaHistory
        */
        public function user_gacha_history()
        {
            return $this->hasOne(UserGachaHistory::class,'point_history_id');
        }



        /**
         * UserPrizeモデル リレーション
         * @return \App\Models\UserPrize
        */
        public function user_prizes()
        {
            return $this->hasMany(UserPrize::class,'point_history_id');
        }



        /**
         * UserShippedモデル リレーション
         * @return \App\Models\UserShipped
        */
        public function user_shipped()
        {
            return $this->hasOne(UserShipped::class,'point_history_id');
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
        public function getreasonAttribute()
        {
            $reasons = $this->reasons();

            return isset( $reasons[ $this->reason_id ] )
            ? $reasons[ $this->reason_id ]
            : 'その他' ;
        }


    //
}
