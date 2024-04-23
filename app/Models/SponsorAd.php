<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/*
| =============================================
|  スポンサー　広告 モデル
| =============================================
*/
class SponsorAd extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;

    protected $fillable = [
        'title'   , //広告タイトル
        'url'     , //サイトURL
        'movie'   , //動画パス
        'memo'    , //特記事項
        'plan_id' , //プランID
        'gacha_id', //ガチャID
        'access_count', //アクセスカウント
        'sponsor_id'
    ];


    /**
     * 広告プラン　一覧
     *
     * @return Array　
     */
    public static function plans()
    {
        return[
            1=>[ ],
        ];
    }




    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * Sponsorモデル リレーション
         * @return \App\Models\Sponsor
        */
        public function sponsor(){
            return $this->belongsTo(Sponsor::class);
        }


        /**
         * Gachaモデル リレーション
         * @return \App\Models\Gacha
        */
        public function gacha(){
            return $this->belongsTo(Gacha::class);
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 動画ファイルパス movie_path
         * @return String
        */
        public function getMoviePathAttribute()
        {
            return $this->movie && Storage::exists($this->movie) ?
            asset( 'storage/'.$this->movie ) : null;
        }

}
