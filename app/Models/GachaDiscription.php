<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  ガチャの詳細説明情報　モデル
| =============================================
*/
class GachaDiscription extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
       'gacha_id', //ガチャリレーション
       'image',//画像
       'sorce',//説明文
       'rank_id',//ランクID
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaDiscriptionFactory::new();
    }
}
