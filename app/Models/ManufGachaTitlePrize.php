<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/*
| =============================================
|  Manufacturer用　ガチャタイトル商品 モデル
| =============================================
*/
class ManufGachaTitlePrize extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'manuf_gacha_title_id',
        'prize_id',
        'order',
        'published_at',
    ];



    protected $casts = [
        'published_at' => 'datetime',
    ];



    /**
     * アクセサーをJSONに含める
     */
    protected $appends = [

    ];



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        public function gachaTitle()
        {
            return $this->belongsTo(ManufGachaTitle::class, 'manuf_gacha_title_id')
            ->withTrashed();
        }

        public function prize()
        {
            return $this->belongsTo(Prize::class)
            ->withTrashed();
        }


        
    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */


}
