<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ユーザー取得商品　モデル
| =============================================
*/

class UserPrize extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',    //ユーザー　リレーション
        'prize_id',   //商品リレーション
        'gacha_history_id',//主テーブルに関連する従テーブルのレコードを削除
        'point_history_id',//ポイント収支履歴リレーション（ポイント交換した時のみ）
        'shipped_id',//発送履歴（発送した時のみ）
        'point',  //(商品取得時の)交換ポイント値
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserPrizeFactory::new();
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
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class);
        }




}
