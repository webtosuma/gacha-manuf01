<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ガチャ履歴　モデル
| =============================================
*/
class UserGachaHistory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',    //ユーザー　リレーション
        'gacha_id',   //ガチャリレーション
        'point_history_id',//ポイント収支履歴リレーション
        'play_count', //ガチャプレイ数
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserGachaHistoryFactory::new();
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
         * Gachaモデル リレーション
         * @return \App\Models\Gacha
        */
        public function gacha(){
            return $this->belongsTo(Gacha::class)->withTrashed();
        }


        /**
         * PointHistoryモデル リレーション
         * @return \App\Models\PointHistory
        */
        public function point_history(){
            return $this->belongsTo(PointHistory::class);
        }


        /**
         * UserPrizeモデル リレーション
         * @return \App\Models\UserPrize
        */
        public function user_prizes()
        {
            return $this->hasMany(UserPrize::class,'gacha_history_id');
        }


}
