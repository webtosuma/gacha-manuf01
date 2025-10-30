<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  買取カテゴリー　モデル
| =============================================
*/
class PurchaseCategory extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'name',        //名前
        'is_published',//公開(bool)
        'order',       //並び順
    ];


    protected $casts = [
        'published_at' => 'datetime',//公開日
    ];


    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * Purchaseモデル リレーション
         * @return \App\Models\Gacha
        */
        public function purchases()
        {
            return $this->hasMany(Purchase::class,'category_id')
            ->orderByDesc('created_at');
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 公開中商品数 published_item_count
         * @return Boolean
        */
        public function getPublishedItemCountAttribute()
        {
            return Purchase::where('category_id',$this->id)
            ->where('published_at', '<' ,now() )
            ->count();
        }



    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ユーザー用一覧 スコープ Category::userList()->get();
         *
         * @return $query
        */
        public function scopeUserList($query)
        {
            $query->where('is_published',1)
            ->orderBy('order')
            ->orderBy('created_at');
        }


        /**
         * Admin用一覧 スコープ GachaCategory::adminList()->get();
         *
         * @return $query
        */
        public function scopeAdminList($query)
        {
            $query->orderBy('order')->orderBy('created_at');
        }


}
