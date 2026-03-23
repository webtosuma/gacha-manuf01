<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  Manufacturer用　ガチャタイトル筐体 モデル
| =============================================
*/
class ManufGachaTitleMachine extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'manuf_gacha_title_id',
        'gacha_id',
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

        public function gacha()
        {
            return $this->belongsTo(Gacha::class)
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
