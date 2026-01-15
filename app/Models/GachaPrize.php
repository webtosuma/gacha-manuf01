<?php

namespace App\Models;
use \App\Http\Controllers\GachaPlayCreateUserPrizeMethod as GPCUPMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
| =============================================
|  ガチャの商品　モデル
| =============================================
*/
class GachaPrize extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'gacha_id', //ガチャの種類リレーション
        'prize_id', //商品リレーション

        'gacha_rank_id',  //ランクID 
        'max_count',      //商品総数
        'remaining_count',//商品残数
        'special_count', //特別なランク専用数 2024/12/23追加　//default(NULL)
    ];


    /**
     * モデルの新ファクトリ・インスタンスの生成
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\GachaPrizeFactory::new();
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

        /**
         * Prizeモデル リレーション
         * @return \App\Models\Prize
        */
        public function prize(){
            return $this->belongsTo(Prize::class);
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー(集計データ)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ガチャランク商品の合計数フォーマット(total_count_fomat)
         * @return String
         */
        public function getTotalCountFormatAttribute()
        {
            $gacha = $this->gacha;

            ## 表示できない当選
            if(
                $this->gacha_rank_id == GPCUPMethod::GachaRankIdPita()    //ピタリ賞の当選
                or $this->gacha_rank_id == GPCUPMethod::GachaRankIdKiri() //キリ番の当選
                or $this->gacha_rank_id == GPCUPMethod::GachaRankIdZoro() //ゾロ目の当選
            )
            {
               return '---';
            }
            ## その他の当選
            else {
                return number_format( round( $this->max_count, 2) );
            }
        }


        /**
         * ガチャランク商品の当選率フォーマット(winning_ratio_format)
         * @return String
         */
        public function getWinningRatioFormatAttribute()
        {
            $gacha = $this->gacha;

            ## 表示できない当選
            if(
                $this->gacha_rank_id == GPCUPMethod::GachaRankIdPita()    //ピタリ賞の当選
                or $this->gacha_rank_id == GPCUPMethod::GachaRankIdKiri() //キリ番の当選
                or $this->gacha_rank_id == GPCUPMethod::GachaRankIdZoro() //ゾロ目の当選
            )
            {
               return '---';
            }
            ## その他の当選
            else {
                $ratio = $gacha->max_count
                ? $this->max_count/$gacha->max_count*100 :0;

                 return round( $ratio, 2) .'%';
            }
        }



        /**
         * ガチャランク商品の残数フォーマット(remaining_count_format)
         * @return String
         */
        public function getRemainingCountFormatAttribute()
        {
            $gacha = $this->gacha;

            ## 表示できない当選
            if(
                $this->gacha_rank_id == GPCUPMethod::GachaRankIdPita()    //ピタリ賞の当選
                or $this->gacha_rank_id == GPCUPMethod::GachaRankIdKiri() //キリ番の当選
                or $this->gacha_rank_id == GPCUPMethod::GachaRankIdZoro() //ゾロ目の当選
            )
            {
               return '---';
            }
            ## その他の当選
            else {
                $ratio = $gacha->max_count
                ? $this->max_count/$gacha->max_count*100 :0;

                 return number_format($this->remaining_count);
            }
        }
}
