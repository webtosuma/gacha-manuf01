<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  演出動画　モデル
| =============================================
*/
class Movie extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',          //動画名
        'pc_storage',    //PC用動画・保存先
        'mobile_storage',//mobile用動画・保存先
    ];


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * PC動画ファイルパス pc
         * @return String
        */
        public function getPcAttribute()
        {
            $no_movie = null;

            return $this->pc_storage && Storage::exists($this->pc_storage) ?
            asset( 'storage/'.$this->pc_storage ) :  $no_movie;
        }



        /**
         * mobile動画ファイルパス mobile
         * @return String
        */
        public function getMobileAttribute()
        {
            $no_movie = null;

            return $this->mobile_storage && Storage::exists($this->mobile_storage) ?
            asset( 'storage/'.$this->mobile_storage ) :  $no_movie;
        }



        /**
         * Youtube動画URL youtube_url
         * @return String
        */
        public function getYoutubeUrlAttribute()
        {

            if (  strpos( $this->mobile_storage, "https://www.youtube.com/shorts" ) !== 0 ){ return null; }


            return $this->mobile_storage ;
        }


        /**
         * 動画の種類 type
         * @return String
        */
        public function getTypeAttribute()
        {

            if (  strpos( $this->mobile_storage, "https://www.youtube.com/shorts" ) === 0 ){ return 'youtube'; }


            return 'mobile' ;
        }

}
