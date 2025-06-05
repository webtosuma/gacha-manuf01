<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointHistory;
use App\Models\User;
use App\Models\UserGachaHistory;
use Carbon\Carbon;
/*
| =============================================
|  サイト管理者 ポイント売上用メソッド　（日別）
| =============================================
*/
class AdminPointHistoryDaily extends Controller
{

    /** ポイントの入出理由ID (ポイント購入) */
    public static function ReasonId(){ return 11; }



    /**
     * 日別の売上
     *
     * @param Carbon\Carbon $date
     * @param User
     * @return mixed
    */
    public static function Sales($date)
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        $query = PointHIstory::query();

            $query->adominPointHistoryReason();//入出ID絞り込み スコープ

        return $query->whereDate('created_at',$date)->sum('price');
    }




    /**
     * 日別の顧客レポート
     *
     * @param Carbon\Carbon $date
     * @param User
     * @return mixed
    */
    public static function Visiters($date)
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();


        # 訪問者ID
        $query = PointHIstory::query();

            $query->adominPointHistoryReason();//入出ID絞り込み スコープ

            $query->whereDate('created_at',$date);

        $id_array = $query->get()->pluck('user_id')->toArray();


        # 訪問者データ
        $visiters = User::find( $id_array );

        # ポイント購入金額データの追加
        foreach ($visiters as $visiter) {
            $visiter->point_price = self::VisiterPointPrice( $date, $visiter );
        }


        return $visiters;
    }



    /**
     * 日別の顧客のポイント購入金額
     *
     * @param Carbon\Carbon $date
     * @param App\Models\User $visiter
     * @return mixed
    */
    public static function VisiterPointPrice( $date, $visiter )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        $query = PointHIstory::query();

            $query->adominPointHistoryReason();//入出ID絞り込み スコープ

        return $query->whereDate('created_at',$date)
        ->where('user_id', $visiter->id)
        ->sum('price');
    }



    /**
     * 日別のリピーター数
     *
     * @param User $visiters
     * @param App\Models\User $visiter
     * @return mixed
    */
    public static function RepeaterCount( $visiters, $date )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        $count = 0;
        foreach ($visiters as $visiter) {

            $query = PointHIstory::query();

                $query->adominPointHistoryReason();//入出ID絞り込み スコープ

                $query->where('created_at','<=',$date->format('Y-m-d 23:59:59'))
                ->where('user_id', $visiter->id);

            $visiter_count = $query->count();

            if( $visiter_count >1 ){ $count ++; }
        }
        return $count;
    }




    /**
     * 日別の購入数
     *
     * @param Carbon\Carbon $month
     * @param App\Models\User $visiter
     * @return mixed
    */
    public static function PaymentCount( $date )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();


        # データ抽出
        $query = PointHistory::query();

            $query->whereDate('created_at',$date);

            $query->adominPointHistoryReason();//入出ID絞り込み スコープ

        return $query->count();
    }



    /**
     * 日別のガチャ回転数
     *
     * @param Carbon\Carbon $month
     * @param App\Models\User $visiter
    */
    public static function GachaPlayCount( $date )
    {
        # データ抽出
        $query = UserGachaHistory::query();

            $query->whereDate('created_at',$date);

        return $query->sum('play_count');
    }
}
