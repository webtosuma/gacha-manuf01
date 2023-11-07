<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'point_sail_id',  //販売ポイント　リレーション
        'user_id',        //ユーザー　リレーション
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
         * PointSailモデル リレーション
         * @return \App\Models\PointSail
        */
        public function point_sail(){
            return $this->belongsTo(PointSail::class);
        }

}
