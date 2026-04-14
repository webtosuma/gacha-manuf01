<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
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

        public function gacha_title()
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

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->path && Storage::exists($this->path) ?
            asset( 'storage/'.$this->path ) :  self::noImage();
        }

}
