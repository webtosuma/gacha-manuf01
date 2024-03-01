<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  販売商品 買い物カゴ　モデル
| =============================================
*/
class UserStoreKeep extends Model
{
    use HasFactory;

    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'user_id',  //ユーザーID
        'store_id', //販売商品ID
        'count',    //数量
    ];



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * Storeモデル リレーション
         * @return \App\Models\Store
        */
        public function store(){
            return $this->belongsTo(Store::class);
        }


}
