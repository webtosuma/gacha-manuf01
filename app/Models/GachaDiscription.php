<?php

namespace App\Models;
use \App\Http\Controllers\GachaPlayCreateUserPrizeMethod as GPCUPMethod;
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


    /** ガチャランク　一覧 */ //ガチャ詳細・ガチャ商品登録のランクの並び
    public static function gacha_ranks()
    {
        return [

            100 => 'RankSS',
            200 => 'RankS',
            300 => 'RankA',

            400 => 'RankB',
            500 => 'RankC',
            600 => 'RankD',

            901 => 'シークレット・キリ',
            903 => 'シークレット・ピタリ',

            320 => 'ゾロ目',
            310 => 'キリ番',
            330 => 'ピタリ賞',

            362 => '個人ゾロ目',
            361 => '個人キリ番',
            363 => '個人ピタリ賞',

            10  => 'ラストワン',

            1001  => 'スライド表示',

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



    /*
    |--------------------------------------------------------------------------
    | アクセサー(集計データ)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 商品の当選するガチャPLAY数(hit_nums)
         * @return String
         */
        public function getHitNumsAttribute()
        {
            $array = $this->hit_nums_array;
            return count($array) ? implode('、',  $array) : '当選なし';
        }

            /**
             * 商品の当選するガチャPLAY数の配列(hit_nums_array)
             * @return Array
             */
            public function getHitNumsArrayAttribute()
            {
                $gacha = $this->gacha;

                ## 個人ピタリ賞の当選
                if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserPita() )
                {
                    $array = GPCUPMethod::PitaHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserPita() );
                }
                ## 個人キリ番の当選
                else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserKiri() )
                {
                    $array = GPCUPMethod::KiriHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserKiri() );
                }
                ## 個人ゾロ目の当選
                else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserZoro() )
                {
                    $array = GPCUPMethod::ZoroHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserZoro() );
                }


                ## ピタリ賞の当選
                else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdPita() )
                {
                    $array = GPCUPMethod::PitaHitPlayCountArray( $gacha );
                }
                ## キリ番の当選
                else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdKiri() )
                {
                    $array = GPCUPMethod::KiriHitPlayCountArray( $gacha );
                }
                ## ゾロ目の当選
                else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdZoro() )
                {
                    $array = GPCUPMethod::ZoroHitPlayCountArray( $gacha );
                }

                ## シークレット・ピタリの当選
                else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretPita() )
                {
                    $array = GPCUPMethod::PitaHitPlayCountArray( $gacha, GPCUPMethod::GachaRankIdSecretPita() );
                }
                ## シークレット・キリの当選
                else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretKiri() )
                {
                    $array = GPCUPMethod::KiriHitPlayCountArray( $gacha, GPCUPMethod::GachaRankIdSecretKiri() );
                }

                ## 通常の当選
                else {
                    $array = [];
                }


                return $array;
            }


        /**
         * ガチャランク商品の合計数(total_count_format)
         * @return String
         */
        public function getTotalCountFormatAttribute()
        {
            $gacha = $this->gacha;

            ## ラストワンの当選
            if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdLastone() )
            {
                $value = $this->g_prizes->sum('max_count');
            }


            ## 個人ピタリ賞の当選
            if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserPita() )
            {
                $array = GPCUPMethod::PitaHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserPita() );
                $value = count( $array );
            }
            ## 個人キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserKiri() )
            {
                $array = GPCUPMethod::KiriHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserKiri() );
                $value = count( $array );
            }
            ## 個人ゾロ目の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserZoro() )
            {
                $array = GPCUPMethod::ZoroHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserZoro() );
                $value = count( $array );
            }


            ## ピタリ賞の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdPita() )
            {
                $array = GPCUPMethod::PitaHitPlayCountArray( $gacha );
                $value = count( $array );
            }
            ## キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdKiri() )
            {
                $array = GPCUPMethod::KiriHitPlayCountArray( $gacha );
                $value = count( $array );
            }
            ## ゾロ目の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdZoro() )
            {
                $array = GPCUPMethod::ZoroHitPlayCountArray( $gacha );
                $value = count( $array );
            }


            ## シークレット・ピタリの当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretPita() )
            {
                $array = GPCUPMethod::PitaHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdSecretPita() );
                $value = count( $array );
            }
            ## シークレット・キリの当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretKiri() )
            {
                $array = GPCUPMethod::KiriHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdSecretKiri() );
                $value = count( $array );
            }


            ## 通常の当選
            else {
                $value = $this->g_prizes->sum('max_count');
            }


            return number_format($value);
        }



        /**
         * ガチャランク商品の当選率(winning_ratio_format)
         * @return tring
         */
        public function getWinningRatioFormatAttribute()
        {
            $gacha = $this->gacha;

            ## ラストワンの当選
            if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdLastone() )
            {
                $ratio = $gacha->max_count
                ? $this->g_prizes->sum('max_count')/$gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }


            ## 個人ピタリ賞の当選
            if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserPita() )
            {
                $array = GPCUPMethod::PitaHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserPita() );
                $ratio = $gacha->max_count ? count( $array ) / $gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }
            ## 個人キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserKiri() )
            {
                $array = GPCUPMethod::KiriHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserKiri() );
                $ratio = $gacha->max_count ? count( $array ) / $gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }
            ## 個人ゾロ目の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserZoro() )
            {
                $array = GPCUPMethod::ZoroHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserZoro() );
                $ratio = $gacha->max_count ? count( $array ) / $gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }


            ## ピタリ賞の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdPita() )
            {
                $ratio = $gacha->max_count
                ? count( GPCUPMethod::PitaHitPlayCountArray( $gacha ) ) / $gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }
            ## キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdKiri() )
            {
                $ratio = $gacha->max_count
                ? count( GPCUPMethod::KiriHitPlayCountArray( $gacha ) ) / $gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }
            ## ゾロ目の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdZoro() )
            {
                $ratio = $gacha->max_count
                ? count( GPCUPMethod::ZoroHitPlayCountArray( $gacha ) ) / $gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }


            ## シークレット・ピタリの当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretPita() )
            {
                $array = GPCUPMethod::PitaHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdSecretPita() );
                $ratio = $gacha->max_count ? count( $array ) / $gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }
            ## シークレット・キリの当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretKiri() )
            {
                $array = GPCUPMethod::KiriHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdSecretKiri() );
                $ratio = $gacha->max_count ? count( $array ) / $gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }


            ## 通常の当選
            else {
                $ratio = $gacha->max_count
                ? $this->g_prizes->sum('max_count')/$gacha->max_count*100 :0;
                $value = round( $ratio, 2);
            }

            return $value.'%';
        }



        /**
         * ガチャランク商品の平均pt(average_point_format)
         * @return String
         */
        public function getAveragePointFormatAttribute()
        {
            $gacha = $this->gacha;

            ## ラストワンの当選
            if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdLastone() )
            {
                $couont = $this->g_prizes->sum('max_count');
                $value = $couont ? $this->total_point / $couont : 0;
            }


            ## 個人ピタリ賞の当選
            if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserPita() )
            {
                $array = GPCUPMethod::PitaHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserPita() );
                $count = count( $array );
                $total_point = 0;
                $g_prizes = $this->g_prizes;
                foreach ($g_prizes as $g_prize) {
                    $total_point +=  $g_prize->prize->point/*ポイント数*/ ;
                }
                $value = $count ? $total_point / $count : 0;
            }
            ## 個人キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserKiri() )
            {
                // $array = GPCUPMethod::KiriHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserKiri() );
                $count = $this->g_prizes->count();
                $total_point = 0;
                $g_prizes = $this->g_prizes;
                foreach ($g_prizes as $g_prize) {
                    $total_point +=  $g_prize->prize->point/*ポイント数*/ ;
                }
                $value = $count ? $total_point / $count : 0;
            }
            ## 個人ゾロ目の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserZoro() )
            {
                // $array = GPCUPMethod::ZoroHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdUserZoro() );
                $count = $this->g_prizes->count();
                $total_point = 0;
                $g_prizes = $this->g_prizes;
                foreach ($g_prizes as $g_prize) {
                    $total_point +=  $g_prize->prize->point/*ポイント数*/ ;
                }
                $value = $count ? $total_point / $count : 0;
            }



            ## ピタリ賞の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdPita() )
            {
                $count = count( GPCUPMethod::PitaHitPlayCountArray( $gacha ) );
                $total_point = 0;
                $g_prizes = $this->g_prizes;
                foreach ($g_prizes as $g_prize) {
                    $total_point +=  $g_prize->prize->point/*ポイント数*/ ;
                }
                $value = $count ? $total_point / $count : 0;
            }
            ## キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdKiri() )
            {
                $count = $this->g_prizes->count();
                $total_point = 0;
                $g_prizes = $this->g_prizes;
                foreach ($g_prizes as $g_prize) {
                    $total_point +=  $g_prize->prize->point/*ポイント数*/ ;
                }
                $value = $count ? $total_point / $count : 0;
            }

            ## ゾロ目の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdZoro() )
            {
                $count = $this->g_prizes->count();
                $total_point = 0;
                $g_prizes = $this->g_prizes;
                foreach ($g_prizes as $g_prize) {
                    $total_point +=  $g_prize->prize->point/*ポイント数*/ ;
                }
                $value = $count ? $total_point / $count : 0;
            }


            ## シークレット・ピタリ賞の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretPita() )
            {
                $array = GPCUPMethod::PitaHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdSecretPita() );
                $count = count( $array );
                $total_point = 0;
                $g_prizes = $this->g_prizes;
                foreach ($g_prizes as $g_prize) {
                    $total_point +=  $g_prize->prize->point/*ポイント数*/ ;
                }
                $value = $count ? $total_point / $count : 0;
            }
            ## シークレット・キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretKiri() )
            {
                $array = GPCUPMethod::KiriHitPlayCountArray( $gacha,  GPCUPMethod::GachaRankIdSecretKiri() );
                $count = $this->g_prizes->count();
                $total_point = 0;
                $g_prizes = $this->g_prizes;
                foreach ($g_prizes as $g_prize) {
                    $total_point +=  $g_prize->prize->point/*ポイント数*/ ;
                }
                $value = $count ? $total_point / $count : 0;
            }


            ## 通常の当選
            else {
                $value = $this->g_prizes->sum('max_count') ?
                $this->total_point / $this->g_prizes->sum('max_count') : 0;
            }

            return number_format($value).'pt';
        }



        /**
         * ガチャランク商品の残数(remaining_count_format)
         * @return String
         */
        public function getRemainingCountFormatAttribute()
        {
            $gacha = $this->gacha;
            $played_count = $gacha->played_count;    //済み口数

            ## ラストワンの当選
            if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdLastone() )
            {
                $value = $this->g_prizes->sum('remaining_count');
            }


            ## 個人ピタリ賞の当選
            if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserPita() )
            {
                return '---';
            }
            ## 個人キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserKiri() )
            {
                return '---';
            }
            ## 個人ゾロ目の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdUserZoro() )
            {
                return '---';
            }



            ## ピタリ賞の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdPita() )
            {
                $array = GPCUPMethod::PitaHitPlayCountArray( $gacha ) ;
                $array = array_filter($array, function($number) use ($played_count) {
                    return $number > $played_count;
                });
                $value = count( $array );
            }
            ## キリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdKiri() )
            {
                $array = GPCUPMethod::KiriHitPlayCountArray( $gacha ) ;
                $array = array_filter($array, function($number) use ($played_count) {
                    return $number > $played_count;
                });
                $value = count( $array );
            }
            ## ゾロ目の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdZoro() )
            {
                $array = GPCUPMethod::ZoroHitPlayCountArray( $gacha ) ;
                $array = array_filter($array, function($number) use ($played_count) {
                    return $number > $played_count;
                });
                $value = count( $array );
            }


            ## シークレットピタリ賞の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretPita() )
            {
                return '---';
            }
            ## シークレットキリ番の当選
            else if( $this->gacha_rank_id == GPCUPMethod::GachaRankIdSecretKiri() )
            {
                return '---';
            }


            ## 通常の当選
            else {
                $value = $this->g_prizes->sum('remaining_count');
            }

            return number_format($value);
        }

    /**/

}
