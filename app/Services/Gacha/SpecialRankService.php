<?php

namespace App\Services\Gacha;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use Ramsey\Uuid\Type\Integer;

/*
| =============================================
|  ガチャ 特殊なガチャランク　 サービス
| =============================================
*/
class SpecialRankService
{
    /**
     * 特殊なガチャランク 一覧 (判定優先順位順)
    */
    public function getList(): array
    { return [

        # ラストワン
        'lastone' => 10,
        
        # XX ピタリ 
        'ss_pita' => 173,
        's_pita'  => 273,
        'a_pita'  => 373,

        # キリ番・ゾロ目・ピタリ賞
        'pita'    => 330,
        'kiri'    => 310,
        'zoro'    => 320,
        
        #　個人 (キリ番・ゾロ目・ピタリ賞)
        'user_pita'    => 363,
        'user_kiri'    => 361,
        'user_zoro'    => 362,

        #　シークレット (キリ番・ピタリ賞)
        'secret_pita'    => 903,
        'secret_kiri'    => 901,

        
        # スライド表示
        'slide'        => 1001,

    ]; }



    /**
     * ラストワンの当たり目
     * @param  Gacha $gacha
    */
    public function hitNumLastone( $gacha ):? int
    {
        # ガチャランクID
        $rank_key = 'lastone';
        $rank_id = $this->getList()[$rank_key];

        # ガチャランクIDを指定して、ガチャ商品の取得(単数)
        $all_g_prizes = $gacha->g_prizes;//すべてのガチャ商品
        $g_prize      = $all_g_prizes->firstWhere('gacha_rank_id', $rank_id);

        # 当たり目を返す(該当ガチャ商品があるときのみ)
        return $g_prize ? $gacha->max_count : null;
    }



    /**
     * キリ番（当たりPLAY数配列）
     * @param  Gacha $gacha
     * @param  String $rank_key //ガチャランクの種類
    */
    public function hitNumsKiri( $gacha, $rank_key='kiri' ): array
    {
        # ガチャランクID
        $rank_id = $this->getList()[$rank_key];

        # 合計口数
        $max_count = $gacha->max_count;

        # ガチャランクIDを指定して、ガチャ商品の取得(複数)
        $all_g_prizes = $gacha->g_prizes;//すべてのガチャ商品
        $g_prizes     = $all_g_prizes->where('gacha_rank_id', $rank_id);

        # 該当商品がなければ、空の配列を返す
        if( !$g_prizes->count() ){ return []; }

        # キリ番の当選するplay数の配列(array)
        $array = [];
        $kiri_bet_count = $g_prizes->first()->special_count;//当選の間隔
        for ($n=1; $n<=$max_count; $n++) {
            if( ($n % $kiri_bet_count) == 0 ){ $array[] = $n; }
        }

        return $array;
    }



    /**
     * ゾロ目（当たりPLAY数配列）
     * @param  Gacha $gacha
     * @param  String $rank_key //ガチャランクの種類
    */
    public function hitNumsZoro( $gacha, $rank_key='zoro' ): array
    {
        # ガチャランクID
        $rank_id = $this->getList()[$rank_key];

        # ガチャランクIDを指定して、ガチャ商品の取得(複数)
        $all_g_prizes = $gacha->g_prizes;//すべてのガチャ商品
        $g_prizes     = $all_g_prizes->where('gacha_rank_id', $rank_id);

        # 該当商品がなければ、空の配列を返す
        if( !$g_prizes->count() ){ return []; }

        # 当選するplay数の配列(array)
        $array = [];
        $max_count = $gacha->max_count; //合計口数
        $n = floor( log10($max_count) );      //合計口数ー下の桁数
        $a = $max_count/10 >1 ? ceil( $max_count / pow(10,$n) ) : 0; //合計口数ー上の位の値（繰り上げ) 10以下の場合ゾロ目なし

        for ($i=1; $i <= ($a==1?9:$a); $i++)
        {
            $repeat_n= $a==1 ? $n : $n+1;
            $num = str_repeat( $i, $repeat_n );

            if( $num <= $max_count ){ $array[] =  (int) $num;}

        }


        return $array;
    }



    /**
     * ピタリ賞（当たりPLAY数配列）
     * @param  Gacha $gacha
     * @param  String $rank_key //ガチャランクの種類
    */
    public function hitNumsPita( $gacha, $rank_key='pita' ): array
    {
        # ガチャランクID
        $rank_id = $this->getList()[$rank_key];

        # ガチャランクIDを指定して、ガチャ商品の取得(複数)
        $all_g_prizes = $gacha->g_prizes;//すべてのガチャ商品
        $g_prizes     = $all_g_prizes->where('gacha_rank_id', $rank_id);

        # 該当商品がなければ、空の配列を返す
        if( !$g_prizes->count() ){ return []; }

        # 当選するplay数の配列(array)
        $array = $g_prizes->pluck('special_count')->toArray();


        return $array;
    }



}
