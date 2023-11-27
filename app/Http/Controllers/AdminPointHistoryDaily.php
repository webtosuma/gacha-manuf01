<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointHistory;
use App\Models\User;
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

        return PointHIstory::where('reason_id',$reason_id)
        // ->whereYear('created_at',$date)
        // ->whereMonth('created_at',$date)
        ->whereDate('created_at',$date)
        ->sum('price');
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

        // $date = now();//TEST

        # 訪問者ID
        $id_array = PointHIstory::where('reason_id',$reason_id)
        ->whereDate('created_at',$date)
        ->pluck('user_id')->toArray();

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

        return PointHIstory::where('reason_id',$reason_id)
        ->whereDate('created_at',$date)
        ->where('user_id', $visiter->id)
        ->sum('price');
    }

}
