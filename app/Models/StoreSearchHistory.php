<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  EC 商品の検索履歴　モデル
| =============================================
*/
class StoreSearchHistory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    protected $fillable = [
        'user_id' ,//ユーザー　　　リレーション
        'keyword' ,//入力値
        'count'   ,//ユーザーの利用回数
        'done_at' ,//履歴非表示の指定日時
    ];


    /** アクセサーをJSONに含める */
    protected $appends = [
        'r_api_destroy',//[ルーティング]API 購入数の変更
    ];


    /** 型指定 */
    protected $casts = [
        'done_at'  => 'datetime',//決済完了日時
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
            return $this->belongsTo(User::class)
            ->withTrashed();//削除済みも含む
        }


    /*
    |--------------------------------------------------------------------------
    | ルーティング
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * [ルーティング]API 購入数の変更 r_api_destroy
         * @return Integer
        */
        public function getRApiDestroyAttribute()
        {
            return ! $this->id ? null : route('store_item.api.search_history.destory', $this->id);
        }


    /* */
}
