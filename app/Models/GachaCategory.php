<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  ガチャのカテゴリーグループ　モデル
| =============================================
*/
class GachaCategory extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',  //名前
        'cord',  //ルーティング用コード
        'is_published',//公開設定(利用しない->非公開*消さない)
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaCategoryFactory::new();
    }
}
