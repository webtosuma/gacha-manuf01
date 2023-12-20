<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  ガチャの詳細説明情報　モデル
| =============================================
*/
class GachaDiscription extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
       'gacha_id', //ガチャリレーション
       'image',//画像
       'sorce',//説明文
       'gacha_rank_id',//ランクID
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaDiscriptionFactory::new();
    }


    /** ガチャランク　一覧 */
    public static function gacha_ranks()
    {
        return [

            100 => 'RankSS',
            200 => 'RankS',
            300 => 'RankA',

            400 => 'RankB',
            500 => 'RankC',
            600 => 'RankD',

            10 => 'ラストワン',
            310 => 'キリ番',
            320 => 'ゾロ目',
        ];
    }

    // /** 特別なガチャランク */
    // public static function special_gacha_rank_ids()
    // {
    //     return [ 10,310,320, ];
    // }

    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * Gachaモデル リレーション
         * @return \App\Models\Gacha
        */
        public function gacha(){
            return $this->belongsTo(Gacha::class, 'gacha_id');
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー 詳細表示
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * GachaPrizeモデル リレーション (g_prizes)
         * @return \App\Models\GachaPrize
        */
        public function getGPrizesAttribute()
        {
            return GachaPrize::has('prize')//リレーションがあるもののみ
            ->where('gacha_id',$this->gacha->id)
            ->where('gacha_rank_id', $this->gacha_rank_id)
            ->get();
        }


        /**
         * ランクのラベルテキスト rank_label
         * @return String
        */
        public function getRankLabelAttribute()
        {
            $gacha_ranks = $this->gacha_ranks();
            return $gacha_ranks[ $this->gacha_rank_id ];
        }


        /**
         * ランクのラベル画像ファイルパス rank_label_image
         * @return String
        */
        public function getRankLabelImageAttribute()
        {
            $dir = 'site/image/rank/';
            $path = $dir.$this->gacha_rank_id.'.png';

            return Storage::exists($path) ?
            asset( 'storage/'.$path ) :  null;
        }


        /**
         * GachaRankMovieモデル リレーション
         * @return \App\Models\GachaRankMovie
        */
        public function getGachaRankMoviesAttribute(){
            return GachaRankMovie::where('gacha_id',$this->gacha->id)
            ->where('gacha_rank_id', $this->gacha_rank_id)
            ->get();
        }


        /**
         * ガチャランクごとの演出動画一覧 (movies)
         * @return \App\Models\GachaPrize
        */
        public function getMoviesAttribute()
        {
            # ガチャランクごとの演出動画ID
            $id_array = GachaRankMovie::where('gacha_id',$this->gacha->id)
            ->where('gacha_rank_id', $this->gacha_rank_id)
            ->get()->pluck('movie_id')->toArray();

            # ガチャランクに紐づく動画
            return Movie::find( $id_array );
        }



        /**
         * ランクの商品ポイント合計 total_point
         * @return String
        */
        public function getTotalPointAttribute()
        {
            $g_prizes = $this->g_prizes;
            $point = 0;
            foreach ($g_prizes as $g_prize) {
                $point +=  $g_prize->prize->point/*ポイント数*/ * $g_prize->max_count/*登録数*/;
            }
            return $point;
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        // /** 画像なしの時の画像 */
        public static function noImage(){ return asset( 'storage/site/image/no_image.jpg' );}

        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            return $this->image && Storage::exists($this->image) ?
            asset( 'storage/'.$this->image ) :  self::noImage();
        }


        /**
         * ストレージ保存された文章（説明文） sorce_text
         * @return String
         */
        public function getSorceTextAttribute()
        {
            // パスから改行を取り除く
            $text = $this->sorce;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }

}
