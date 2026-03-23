<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  Manufacturer用　ガチャタイトル ランク別動画 モデル
| =============================================
*/

class ManufGachaTitleMovie extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'manuf_gacha_title_id',
        'gacha_id',
        'movie_id',
        'gacha_rank_id',
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


        public function movie()
        {
            return $this->belongsTo(Movie::class)
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
