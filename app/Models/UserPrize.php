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
        'gacha_history_id',//入手したガチャのID
        'point_history_id',//ポイント収支履歴リレーション（ポイント交換した時のみ）
        'shipped_id',//発送履歴（発送した時のみ）
        'point',  //(商品取得時の)交換ポイント値
        'ticket_history_id',//チケット収支履歴リレーション(チッケトと交換て入手した時のみ)
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


        /**
         * UserGachaHistoryモデル リレーション
         * @return \App\Models\UserGachaHistory
        */
        public function ug_history(){
            return $this->belongsTo(UserGachaHistory::class,'gacha_history_id');
        }

        /**
         * TicketHistoryモデル リレーション
         * @return \App\Models\TicketHistory
        */
        public function ticket_history(){
            return $this->belongsTo(TicketHistory::class,'ticket_history_id');
        }

    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */
        /** 保有中のみ（全体or個人）  */
        public function scopeOnlyPossessionScope( $query ,$user_id )
        {
            # ログインユーザーのデータに絞る
            if($user_id){
                $query->where('user_id',$user_id);
            }

            # 商品情報とのリレーションがあること
            $query->has('prize');

            # ポイント交換ずみのデータを除く
            $query->where('point_history_id',NULL);

            # 発送済みデータを除く
            $query->where('shipped_id',Null);

            # 取得が新しい順
            $query->orderByDesc('id');
            $query->orderByDesc('created_at');

            # 商品テーブル(prize)とのリレーション
            $query->with(['prize.rank' => function ($query) { }]);

            return $query;
        }

}
