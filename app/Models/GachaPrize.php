<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ガチャの商品　モデル
| =============================================
*/
class GachaPrize extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'gacha_id', //ガチャの種類リレーション
        'prize_id', //商品リレーション

        'gacha_rank_id',  //ランクID
        'max_count',      //商品総数
        'remaining_count',//商品残数
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaPrizeFactory::new();
    }



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class);
        }


        /**
         * Gachaモデル リレーション
         * @return \App\Models\Gacha
        */
        public function gacha()
        {
            return $this->belongsTo(Gacha::class,'gacha_id');
        }

}
