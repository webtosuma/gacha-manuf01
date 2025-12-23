<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  販売商品　モデル
| =============================================
*/
class Store extends Model
{
    use HasFactory;

    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'prize_id',    //商品ID
        'category_id', //カテゴリーID
        'user_id',     //出品者ID

        'ticket_count',//交換チケット数
        'point_count' ,//交換ポイント数
        'published_at',//公開設定(利用しない->非公開*消さない)
        'count',       //在庫数
    ];


    /** Carbonオブジェクトとして利用 */
    protected $dates = [
        'published_at',//公開設定(利用しない->非公開*消さない)
    ];




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
         * GachaCategoryモデル リレーション
         * @return \App\Models\GachaCategory
        */
        public function category(){
            return $this->belongsTo(GachaCategory::class, 'category_id');
        }


        /**
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class)
            ->withTrashed(); // withTrashed() メソッドを追加
        }

}
