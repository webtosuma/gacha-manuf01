<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  ガチャ・ランク別　演出動画　モデル
| =============================================
*/
class GachaRankMovie extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'gacha_id',     //ガチャリレーション
        'movie_id',     //演出動画リレーション
        'gacha_rank_id',//ランクID
    ];



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * Movieモデル リレーション movie
         * @return \App\Models\Movie
        */
        public function movie(){
            return $this->belongsTo(Movie::class);
        }
}
