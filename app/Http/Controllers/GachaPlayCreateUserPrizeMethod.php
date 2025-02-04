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
            $max_count        = $gacha->max_count;          //合計口数
            $played_count     = $gacha->played_count;       //済み口数(処理中に加算あり)
            $remaining_count  = $gacha->remaining_count;    //残り口数(処理中に減算あり)
            $user_playd_count = $gacha->user_played_count-$play_count;//ユーザー済み口数(処理中に加算あり)(ポイント履歴を先に加算してしまうため、-1とする)

            $randReminingGPIdArray = [];//当選したガチャ商品IDの配列


        # 当たりの種類による分岐
        $count = $play_count; //ガチャの実行数
        for ($i=0; $i < $count; $i++)
        {
            $gacha_prize_id = null;


            ## ラストワンの当選
            if( $played_count+1 == self::LastoneHitPlayCount( $gacha ) )//integer
            {
                $gacha_rank_id  = self::GachaRankIdLastone();
                $gacha_prize_id = self::WinnerSpecial( $user_gacha_history, $gacha_rank_id );
            }



            ## 個人ピタリ賞の当選
            else if(
                in_array( $user_playd_count+1, self::PitaHitPlayCountArray( $gacha, self::GachaRankIdUserPita()/*ランクID*/ ) )
            ){
                $gacha_prize_id = self::WinnerPita( $user_gacha_history, $user_playd_count+1, self::GachaRankIdUserPita()/*ランクID*/ );
            }

            ## 個人キリ番の当選
            else if(
                in_array( $user_playd_count+1, self::KiriHitPlayCountArray( $gacha, self::GachaRankIdUserKiri()/*ランクID*/ ) )
            ){
                $gacha_prize_id = self::WinnerKiri( $user_gacha_history, self::GachaRankIdUserKiri()/*ランクID*/ );
            }

            ## 個人ゾロ目の当選
            else if(
                in_array( $user_playd_count+1, self::ZoroHitPlayCountArray( $gacha, self::GachaRankIdUserZoro()/*ランクID*/ ) )
            ){
                $gacha_prize_id = self::WinnerZoro( $user_gacha_history, self::GachaRankIdUserZoro()/*ランクID*/ );
            }



            ## ピタリ賞の当選
            else if(
                in_array( $played_count+1, self::PitaHitPlayCountArray( $gacha ) )
            ){
                $gacha_prize_id = self::WinnerPita( $user_gacha_history, $played_count+1 );
            }

            ## キリ番の当選
            else if(
                in_array( $played_count+1, self::KiriHitPlayCountArray( $gacha ) )
            ){
                $gacha_prize_id = self::WinnerKiri( $user_gacha_history );
            }

            ## ゾロ目の当選
            else if(
                in_array( $played_count+1, self::ZoroHitPlayCountArray( $gacha ) )
            ){
                $gacha_prize_id = self::WinnerZoro( $user_gacha_history );
            }


            ## 通常の当選
            else {
                $gacha_prize_id = self::WinnerNomal( $user_gacha_history );
            }

            // dd($user_playd_count+1);

            $randReminingGPIdArray[] = $gacha_prize_id;
            $played_count     ++;//済み口数の加算
            $remaining_count  --;//残り数の減産
            $user_playd_count ++;//ユーザー済み口数の加算

        }

        return $randReminingGPIdArray;
    }



    /** ガチャランクID ラストワン */
    public static function GachaRankIdLastone()  { return 10; }

    /** ガチャランクID キリ番 */
    public static function GachaRankIdKiri()     { return 310; }
    /** ガチャランクID ゾロ目 */
    public static function GachaRankIdZoro()     { return 320; }
    /** ガチャランクID ピタリ賞 */
    public static function GachaRankIdPita()     { return 330; }

    /** ガチャランクID 個人キリ番 */
    public static function  GachaRankIdUserKiri(){ return 361; }
    /** ガチャランクID 個人ゾロ目 */
    public static function  GachaRankIdUserZoro(){ return 362; }
    /** ガチャランクID 個人ピタリ賞 */
    public static function  GachaRankIdUserPita(){ return 363; }

    /** ガチャランクID スライド表示 */
    public static function  GachaRankIdSlide(){   return 1001; }


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


        // dd($gacha_prize->prize->point);
        # ユーザー取得商品の登録
        $user_prize = new UserPrize([
            'user_id'  => $user->id,  //ユーザー　リレーション
            'prize_id' => $gacha_prize->prize_id,//商品リレーション
            'gacha_history_id'=> $user_gacha_history->id,//主テーブルに関連する従テーブルのレコードを削除
            'point'    => $gacha_prize->prize->point,  //(商品取得時の)交換ポイント値
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

        # ランダムな通常ガチャ商品の取得
        $rand_g_prize = self::GetRandNomalGachaPrize( $user_gacha_history );

        # ユーザー商品の登録・ガチャ商品の残数更新
        self::CreateUserPrizel( $user_gacha_history, $rand_g_prize );


        return $rand_g_prize->id;
    }


        /**
         * ランダムな通常ガチャ商品の取得
         *
         * @param  UserGachaHistory $user_gacha_history
         * @return GachaPprize $gacha_prize //当選した通常ガチャ商品
        */
        public static function GetRandNomalGachaPrize( $user_gacha_history )
        {
            # ガチャ情報
            $gacha = $user_gacha_history->gacha;

            # ガチャ商品情報（特殊な当選は除く）
            $g_prizes = GachaPrize::where( 'gacha_id', $gacha->id )
            ->where('gacha_rank_id','<>',self::GachaRankIdLastone())// ラストワンは除く
            ->where('gacha_rank_id','<>',self::GachaRankIdKiri())// キリ番は除く
            ->where('gacha_rank_id','<>',self::GachaRankIdZoro())// ゾロ目は除く
            ->where('gacha_rank_id','<>',self::GachaRankIdPita())// ピタリ賞は除く
            ->get();


            # 残っているガチャ商品配列（重複含む）$remainingGachaPrizesArray
            $remainingGachaPrizesArray = [];
            foreach ($g_prizes as $g_prize)
            {
                $n = $g_prize->remaining_count;
                for ($i=0; $i < $n; $i++) {

                    $remainingGachaPrizesArray[] = $g_prize;

                }
            }

            # ガチャ商品が集れば、スキップ
            if( !count($remainingGachaPrizesArray) ){ return; }

            # ランダムな当たりを出力
            $key = array_rand($remainingGachaPrizesArray);
            $rand_g_prize = $remainingGachaPrizesArray[$key];


            return $rand_g_prize;
        }/* --- */


    /**
     * 特殊な当選(ラストワン)
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



    /**
     * キリ番の当選処理
     *
     * @param  UserGachaHistory $user_gacha_history
     * @param  Integer $gacha_rank_id //ガチャランクID
     * @return Integer $gacha_prize_id //当選したガチャ商品ID
    */
    public static function WinnerKiri( $user_gacha_history, $gacha_rank_id=null )
    {
        # ガチャランクID
        $gacha_rank_id  = $gacha_rank_id ?? self::GachaRankIdKiri();

        # ガチャ情報
        $gacha = $user_gacha_history->gacha;

        # ランダムな通常ガチャ商品の取得
        $nomal_g_prize = self::GetRandNomalGachaPrize( $user_gacha_history );

        # ガチャの商品：残数の更新
        $nomal_g_prize->remaining_count --;
        $nomal_g_prize->save();


        # ガチャランクに応じた、ガチャ商品情報
        $gacha_prize = GachaPrize::where( 'gacha_id', $gacha->id )
        ->where('gacha_rank_id',$gacha_rank_id )
        ->inRandomOrder()//ランダム抽出
        ->first();

        # ユーザー取得商品の登録
        $user = Auth::user(); //ログインユーザー取得
        $user_prize = new UserPrize([
            'user_id'  => $user->id,                     //ユーザー　リレーション
            'prize_id' => $gacha_prize->prize_id,        //商品リレーション
            'point'    => $gacha_prize->prize->point,    //(商品取得時の)交換ポイント値
            'gacha_history_id'=> $user_gacha_history->id,//主テーブルに関連する従テーブルのレコードを削除
        ]);
        $user_prize->save();


        # 当選したガチャ商品IDを返す
        return $gacha_prize->id;
    }



    /**
     * ゾロ目の当選処理
     *
     * @param  UserGachaHistory $user_gacha_history
     * @param  Integer $gacha_rank_id //ガチャランクID
     * @return Integer $gacha_prize_id //当選したガチャ商品ID
    */
    public static function WinnerZoro( $user_gacha_history, $gacha_rank_id=null )
    {
        # ガチャランクID
        $gacha_rank_id  = $gacha_rank_id ?? self::GachaRankIdZoro();

        # ガチャ情報
        $gacha = $user_gacha_history->gacha;

        # ランダムな通常ガチャ商品の取得
        $nomal_g_prize = self::GetRandNomalGachaPrize( $user_gacha_history );

        # ガチャの商品：残数の更新
        $nomal_g_prize->remaining_count --;
        $nomal_g_prize->save();


        # ガチャランクに応じた、ガチャ商品情報
        $gacha_prize = GachaPrize::where( 'gacha_id', $gacha->id )
        ->where('gacha_rank_id',$gacha_rank_id )
        ->inRandomOrder()//ランダム抽出
        ->first();

        # ユーザー取得商品の登録
        $user = Auth::user(); //ログインユーザー取得
        $user_prize = new UserPrize([
            'user_id'  => $user->id,                     //ユーザー　リレーション
            'prize_id' => $gacha_prize->prize_id,        //商品リレーション
            'point'    => $gacha_prize->prize->point,    //(商品取得時の)交換ポイント値
            'gacha_history_id'=> $user_gacha_history->id,//主テーブルに関連する従テーブルのレコードを削除
        ]);
        $user_prize->save();


        # 当選したガチャ商品IDを返す
        return $gacha_prize->id;
    }



    /**
     * ピタリ賞の当選処理
     *
     * @param  UserGachaHistory $user_gacha_history
     * @param  Integer $special_count  //今のガチャ回数
     * @param  Integer $gacha_rank_id //ガチャランクID
     * @return Integer $gacha_prize_id //当選したガチャ商品ID
    */
    public static function WinnerPita( $user_gacha_history, $special_count, $gacha_rank_id=null )
    {
        # ガチャランクID
        $gacha_rank_id  = $gacha_rank_id ?? self::GachaRankIdPita();

        # ガチャ情報
        $gacha = $user_gacha_history->gacha;

        # ランダムな通常ガチャ商品の取得
        $nomal_g_prize = self::GetRandNomalGachaPrize( $user_gacha_history );

        # ガチャの商品：残数の更新
        $nomal_g_prize->remaining_count --;
        $nomal_g_prize->save();

        # ガチャランクに応じた、ガチャ商品情報
        $gacha_prize = GachaPrize::where( 'gacha_id', $gacha->id )
        ->where('gacha_rank_id',$gacha_rank_id )
        ->where('special_count',$special_count )//今のガチャ回数
        ->inRandomOrder()//ランダム抽出
        ->first();

        # ユーザー取得商品の登録
        $user = Auth::user(); //ログインユーザー取得
        $user_prize = new UserPrize([
            'user_id'  => $user->id,                     //ユーザー　リレーション
            'prize_id' => $gacha_prize->prize_id,        //商品リレーション
            'point'    => $gacha_prize->prize->point,    //(商品取得時の)交換ポイント値
            'gacha_history_id'=> $user_gacha_history->id,//主テーブルに関連する従テーブルのレコードを削除
        ]);
        $user_prize->save();


        # 当選したガチャ商品IDを返す
        return $gacha_prize->id;
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
        public static function LastoneHitPlayCount( $gacha )
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



    /*
    | ---------------------------------------
    |  特殊なランクの当たりPLAY数配列
    | ---------------------------------------
    */


        /**
         * キリ番（当たりPLAY数配列）
         *
         * @param  Gacha $gacha
         * @param  Integer $gacha_rank_id //ガチャランクID
         * @return Array
        */
        public static function KiriHitPlayCountArray( $gacha, $gacha_rank_id=null )
        {
            # ガチャランクID
            $gacha_rank_id = $gacha_rank_id ?? self::GachaRankIdKiri();

            # 変数
            $max_count       = $gacha->max_count;       //合計口数


            # 該当するガチャ商品の取得
            $gacha_prizes = GachaPrize::where('gacha_id',$gacha->id)
            ->where('gacha_rank_id',$gacha_rank_id)
            ->get();

            # 該当商品がなければ、空の配列を返す
            if( !$gacha_prizes->count() ){ return []; }

            # キリ番の当選するplay数の配列(array)
            $array = [];
            $kiri_bet_count = $gacha_prizes[0]->special_count;//当選の間隔
            for ($n=1; $n<=$max_count; $n++) {
                if( ($n % $kiri_bet_count) == 0 ){ $array[] = $n; }
            }


            return $array;
        }



        /**
         * ゾロ目（当たりPLAY数配列）
         *
         * @param  Gacha $gacha
         * @param  Integer $gacha_rank_id //ガチャランクID
         * @return Array
        */
        public static function ZoroHitPlayCountArray( $gacha, $gacha_rank_id=null )
        {
            # ガチャランクID
            $gacha_rank_id = $gacha_rank_id ?? self::GachaRankIdZoro();

            # 該当するガチャ商品の取得
            $gacha_prizes = GachaPrize::where('gacha_id',$gacha->id)
            ->where('gacha_rank_id',$gacha_rank_id)
            ->get();

            # 該当商品がなければ、空の配列を返す
            if( !$gacha_prizes->count() ){ return []; }

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

            // dd($array);

            return $array;
        }



        /**
         * ピタリ賞（当たりPLAY数配列）
         *
         * @param  Gacha $gacha
         * @param  Integer $gacha_rank_id //ガチャランクID
         * @return Array
        */
        public static function PitaHitPlayCountArray( $gacha, $gacha_rank_id=null )
        {
            # ガチャランクID
            $gacha_rank_id = $gacha_rank_id ?? self::GachaRankIdPita();
            // dd($gacha_rank_id);

            # 該当するガチャ商品の取得
            $gacha_prizes = GachaPrize::where('gacha_id',$gacha->id)
            ->where('gacha_rank_id',$gacha_rank_id)
            ->where('special_count','<=',$gacha->max_count)//ガチャの総口数より大きい値を除く
            ->get();

            # 該当商品がなければ、空の配列を返す
            if( !$gacha_prizes->count() ){ return []; }

            # 当選するplay数の配列(array)
            $array = $gacha_prizes->pluck('special_count')->toArray();
            // sort($array);// 昇順にソート


            return $array;
        }
    //
}
