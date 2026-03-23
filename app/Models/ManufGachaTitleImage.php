<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  Manufacturer用　ガチャタイトル画像 モデル
| =============================================
*/
class ManufGachaTitleImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'manuf_gacha_title_id',
        'path',
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


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */


}
