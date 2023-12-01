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
        'win_order', //指定して当選する順番

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


    /**
     * ガチャランク　一覧
     *
     * @return Array　
     */
    public static function gacha_ranks()
    {
        return [
            11 => 'ラストワン',
            21 => 'ゾロ目',

            101 => 'RankSS',
            102 => 'RankS',

            111 => 'RankA',
            112 => 'RankB',
            113 => 'RankC',
            114 => 'RankD',

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
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class);
        }

}
