<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  ガチャ　モデル
| =============================================
*/
class Gacha extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',  //名前
        'image', //イメージ画像
        'one_pray_point', //
        'ten_pray_point', //
        'category_id',  //リレーション
        'ipublished_at',//公開設定(利用しない->非公開*消さない)
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaFactory::new();
    }
}
