<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  商品 ランク　モデル
| =============================================
*/

class PrizeRank extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'order',  //順番
        'name',   //名前
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\PrizeRankFactory::new();
    }

}
