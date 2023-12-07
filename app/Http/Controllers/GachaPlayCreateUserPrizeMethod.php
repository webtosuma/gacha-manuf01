<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\PointHistory;
use App\Models\UserGachaHistory;
use App\Models\UserPrize;
use App\Models\Prize;
use App\Models\GachaRankMovie;
use App\Models\Movie;
/*
| =============================================
|  ガチャ PLAY：当たりの選出・ユーザー取得商品の登録 メソッド
| =============================================
*/
class GachaPlayCreateUserPrizeMethod extends Controller
{

    /**
     * index
     * @param  GUserGachaHistory $user_gacha_history
     * @param  Integer $play_count //ガチャの実行数
     * @return Array $randReminingGPIdArray　//当選したガチャ商品IDの配列
    */
    public static function index($user_gacha_history, $play_count)
    {

        # 変数定義

            $gacha = $user_gacha_history->gacha; //ガチャ情報

            $max_count       = $gacha->max_count;       //合計口数
            $played_count    = $gacha->played_count;    //済み口数
            $remaining_count = $gacha->remaining_count; //残り口数

            $lastone_hit     = self::LastoneHit( $gacha );    //ラストワンの当たり目
            $kiri_hits_array = self::KiriHitsArray( $gacha ); //キリ番の当たり目
            $zoro_hits_array = self::ZoroHitsArray( $gacha ); //ゾロ目の当たり目


            $count = $play_count; //ガチャの実行数
            $randReminingGPIdArray = [];//当選したガチャ商品IDの配列

            // dd($zoro_hits_array);

        # 当たりの種類による分岐
        for ($i=0; $i < $count; $i++)
        {
            $gacha_prize_id = null;

            ## ラストワンの当選
            if( $played_count+1 == $lastone_hit ){
                // dd('ラストワン:'.$played_count+1);
                $gacha_rank_id  = self::GachaRankIdLastone();
                $gacha_prize_id =
                self::WinnerSpecial( $user_gacha_history, $gacha_rank_id );
            }

            ## キリ番の当選
            else if( in_array( $played_count+1, $kiri_hits_array ) ){
                // dd('キリ番:'.$played_count+1);
                $gacha_rank_id  = self::GachaRankIdKiri();
                $gacha_prize_id =
                self::WinnerSpecial( $user_gacha_history, $gacha_rank_id );
            }

            ## ゾロ目の当選
            else if( in_array( $played_count+1, $zoro_hits_array ) ){
                dd('ゾロ目:'.$played_count+1);
                $gacha_rank_id  = self::GachaRankIdZoro();
                $gacha_prize_id =
                self::WinnerSpecial( $user_gacha_history, $gacha_rank_id );            }

            ## 通常の当選
            else {
                $gacha_prize_id = self::WinnerNomal( $user_gacha_history );
            }


            $randReminingGPIdArray[] = $gacha_prize_id;
            $played_count ++;//済み口数の加算
        }

        return $randReminingGPIdArray;
    }



    /** ガチャランクID ラストワン */
    public static function GachaRankIdLastone(){ return 10; }
    /** ガチャランクID キリ番 */
    public static function GachaRankIdKiri()   { return 310; }
    /** ガチャランクID ゾロ目 */
    public static function GachaRankIdZoro()   { return 320; }



    /**
     * ユーザー商品の登録・ガチャ商品の残数更新
     * (通常当選・特殊当選　共通)
     *
     * @param  UserGachaHistory $user_gacha_history
     * @param  GachaPrize  $gacha_prize //当選したガチャ商品
     * @return Void
    */
    public static function CreateUserPrizel( $user_gacha_history, $gacha_prize )
    {
        $user = Auth::user(); //ログインユーザー取得

        # ガチャの商品：残数の更新
        $gacha_prize->remaining_count --;
        $gacha_prize->save();


        # ユーザー取得商品の登録
        $user_prize = new UserPrize([
            'user_id'  => $user->id,  //ユーザー　リレーション
            'prize_id' => $gacha_prize->prize_id,//商品リレーション
            'gacha_history_id'=> $user_gacha_history->id,//主テーブルに関連する従テーブルのレコードを削除
        ]);
        $user_prize->save();
    }



    /**
     * 通常の当選
     *
     * @param  UserGachaHistory $user_gacha_history
     * @return Integer $gacha_prize_id //当選したガチャ商品ID
    */
    public static function WinnerNomal( $user_gacha_history )
    {
        $gacha = $user_gacha_history->gacha; //ガチャ情報

        # ガチャ商品情報（特殊な当選は除く）
        $g_prizes = GachaPrize::where( 'gacha_id', $gacha->id )
        ->where('gacha_rank_id','<>',self::GachaRankIdLastone())// ラストワンは除く
        ->where('gacha_rank_id','<>',self::GachaRankIdKiri())// キリ番は除く
        ->where('gacha_rank_id','<>',self::GachaRankIdZoro())// ゾロ目は除く
        ->get();
        // dd( $g_prizes->toArray() );


        # 残っているガチャ商品配列（重複含む）$remainingGachaPrizesArray
        $remainingGachaPrizesArray = [];
        foreach ($g_prizes as $g_prize)
        {
            $n = $g_prize->remaining_count;
            for ($i=0; $i < $n; $i++) {

                $remainingGachaPrizesArray[] = $g_prize;

            }
        }
        // dd( count( $remainingGachaPrizesArray ) );
        // if( !count($remainingGachaPrizesArray) ){ dd( $user_gacha_history->user_prizes->toArray() ); }
        if( !count($remainingGachaPrizesArray) ){ return; }




        # ランダムな当たりを出力
        $key = array_rand($remainingGachaPrizesArray);
        $rand_g_prize = $remainingGachaPrizesArray[$key];
        // dd( $rand_g_prize );


        # ユーザー商品の登録・ガチャ商品の残数更新
        self::CreateUserPrizel( $user_gacha_history, $rand_g_prize );


        return $rand_g_prize->id;
    }



    /**
     * 特殊な当選
     *
     * @param  UserGachaHistory $user_gacha_history
     * @param  Ineger $gacha_rank_id
     * @return Integer $gacha_prize_id //当選したガチャ商品ID
    */
    public static function WinnerSpecial( $user_gacha_history, $gacha_rank_id )
    {
        $gacha = $user_gacha_history->gacha; //ガチャ情報

        # ガチャ商品情報（特殊な当選は除く）
        $g_prize = GachaPrize::where( 'gacha_id', $gacha->id )
        ->where('gacha_rank_id',$gacha_rank_id )
        ->first();

        # ユーザー商品の登録・ガチャ商品の残数更新
        self::CreateUserPrizel( $user_gacha_history, $g_prize );

        return $g_prize->id;

    }


    /*
    | ---------------------------------------
    |  当たり目
    | ---------------------------------------
    */
        /**
         * ラストワンの当たり目
         *
         * @param  Gacha $gacha
         * @return Integer 当たり目
        */
        public static function LastoneHit( $gacha )
        {
            # ガチャランクID
            $gacha_rank_id = self::GachaRankIdLastone();

            # 該当するガチャ商品の取得
            $gacha_prize = GachaPrize::where('gacha_id',$gacha->id)
            ->where('gacha_rank_id',$gacha_rank_id)
            ->first();


            # 当たり目を返す(該当ガチャ商品があるときのみ)
            return $gacha_prize ? $gacha->max_count : null;
        }


        /**
         * キリ番の当たり目
         *
         * @param  Gacha $gacha
         * @return Array 当たり目配列
        */
        public static function KiriHitsArray( $gacha )
        {
            # ガチャランクID
            $gacha_rank_id = self::GachaRankIdKiri();

            # 該当するガチャ商品の取得
            $gacha_prize = GachaPrize::where('gacha_id',$gacha->id)
            ->where('gacha_rank_id',$gacha_rank_id)
            ->get();


            # 当たり目を返す(該当ガチャ商品があるときのみ)
            return $gacha_prize->count() ? self::KiriHitsCalc( $gacha->max_count ) : [] ;

        }


            /**
             * キリ番（当たり目の算出）
             *
             * @param  Integer $max_count //合計口数
             * @return Array 当たり目配列
            */
            public static function KiriHitsCalc( $max_count )
            {
                $n = floor( log10($max_count) );      //合計口数ー下の桁数
                $a = ceil( $max_count / pow(10,$n) ); //合計口数ー上の位の値（繰り上げ

                $nums = [];
                for ($i=1; $i <= $a; $i++)
                {
                    $num = $i * pow(10,$n);
                    if( $num <= $max_count ){ $nums[] = $num;}
                }

                return $nums;
            }




        /**
         * ゾロ目の当たり目
         *
         * @param  Gacha $gacha
         * @return Array 当たり目配列
        */
        public static function ZoroHitsArray( $gacha )
        {
            # ガチャランクID
            $gacha_rank_id = self::GachaRankIdZoro();

            # 該当するガチャ商品の取得
            $gacha_prize = GachaPrize::where('gacha_id',$gacha->id)
            ->where('gacha_rank_id',$gacha_rank_id)
            ->get();


            # 当たり目を返す(該当ガチャ商品があるときのみ)
            return $gacha_prize->count() ? self::ZoroHitsCalc( $gacha->max_count ) : [] ;

        }


            /**
             * ゾロ目（当たり目の算出）
             *
             * @param  Integer $max_count //合計口数
             * @return Array 当たり目配列
            */
            public static function ZoroHitsCalc( $max_count )
            {
                $n = floor( log10($max_count) );      //合計口数ー下の桁数
                $a = ceil( $max_count / pow(10,$n) ); //合計口数ー上の位の値（繰り上げ

                $nums = [];
                for ($i=1; $i <= $a; $i++)
                {
                    $num = str_repeat( $i, $n+1 );
                    if( $num <= $max_count ){ $nums[] = $num;}

                }

                return $nums;
            }


    //
}
