<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  販売用ポイント　モデル
| =============================================
*/
class PointSail extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'value',  //実際付与されるポイント
        'price',  //管理者編集権限
        'service',//サービス差異
        'is_subscription', //サブスクリプションか否か
        'is_published',    //公開設定(利用しない->非公開*消さない)
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\PointSailFactory::new();
    }
}
