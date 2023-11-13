<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ガチャの景品　モデル
| =============================================
*/
class GachaPrize extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
       'gacha_id', //ガチャリレーション
       'prize_id', //景品リレーション

        'rank_id',        //ランクID
        'max_count',      //景品総数
        'remaining_count',//景品残数
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
}
