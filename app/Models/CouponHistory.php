<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  クーポン履歴　テーブル
| =============================================
*/
class CouponHistory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id'         ,//ユーザーリレーション
        'coupon_id'       ,//クーポンリレーション
        'point_history_id',//ポイント履歴(ポイント交換の時)
        'user_prize_id'   ,//ガチャ商品ID(商品交換の時)
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\CouponHistoryFactory::new();
    }
}
