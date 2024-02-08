<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  景品発送履歴　テーブル
| =============================================
*/
class UserShipped extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',         //ユーザー　リレーション
        'user_address_id', //ユーザーアドレス
        'point_history_id',//ポイント収支履歴リレーション
        'state_id',        //発送状況
        'shipment_at',     //発送日時
        'shipment_read',   //ユーザーの発送確認
        'arrival_at' ,     //到着日時
    ];

    /** Carbonオブジェクトとして利用 */
    protected $dates = [
        'shipment_at',     //発送日時
        'arrival_at' ,     //到着日時
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserShippedFactory::new();
    }


    /**
     * 景品発送状況
     *
     * @return Array　
     */
    public static function state()
    {
        return [
            //発送前
            11 => '発送待ち',

            //発送後
            21 => '発送済み',

            //ユーザー
            31 => '受取済み',
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
         * PointHistoryモデル リレーション
         * @return \App\Models\PointHistory
        */
        // public function user_address(){
        //     return $this->belongsTo(UserAddress::class);
        // }
        public function user_address(){
            return $this->belongsTo(UserAddress::class)
            ->withTrashed(); // withTrashed() メソッドを追加
        }

        /**
         * UserPrizeモデル リレーション
         * @return \App\Models\UserPrize
        */
        public function user_prizes()
        {
            return $this->hasMany(UserPrize::class,'shipped_id');
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 発送コード code 0000-0000
         * @return String
        */
        public function getCodeAttribute()
        {
            return sprintf('s%04d-%04d',$this->user_id,$this->id);
        }
}
