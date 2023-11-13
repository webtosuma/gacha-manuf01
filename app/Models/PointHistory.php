<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
| =============================================
|  ポイント購入履歴　モデル
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
    public static function resons()
    {
        return [
            //ポイント加算
            11 => 'ポイント購入',
            12 => '景品のポイント交換',

            //ポイント減算
            21 => 'ガチャる',
            22 => '景品発送',
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

}
