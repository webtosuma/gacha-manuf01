<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointHistory;
use Carbon\Carbon;
use App\Models\User;

use App\Http\Controllers\AdminPointHistoryDaily as Daily;
/*
| =============================================
|  サイト管理者 ポイント売上用メソッド　（月別）
| =============================================
*/
class AdminPointHistoryMonthly extends Controller
{

    /** ポイントの入出理由ID (ポイント購入) */
    public static function ReasonId(){ return 11; }



    /**
     * 月別の日別レポート
     *
     * @param Carbon\Carbon
     * @return mixed
    */
    public static function DayReports($month)
    {
        // 月初日
        $date = Carbon::parse( $month->format('Y-m-01') );
        // 月末日
        $end_date = $date->copy()->endOfMonth();
        $end_date = $end_date > now() ? now() : $end_date;

        # 日別レポート保存
        $day_reports = [];
        while( $date->format('Y-m-d') <= $end_date->format('Y-m-d') )
        {
            $day_reports[] = [
                'date_text'=> $date->format('Y-m-d'),
                'date'     => $date->copy(),
                'sales'    => Daily::Sales($date),    //日別の売上
                'visiters' => Daily::Visiters($date), //日別の顧客レポート
            ];
            $date->addDay();
        }


        return $day_reports;
    }




    /**
     * 月間の合計売上
     *
     * @param Carbon\Carbon $month
     * @param User
     * @return mixed
    */
    public static function Sales( $month )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        return PointHIstory::where('reason_id',$reason_id)
        ->whereYear('created_at',$month)
        ->whereMonth('created_at',$month)
        ->sum('price');
    }




    /**
     * 月間の顧客レポート
     *
     * @param Carbon\Carbon $month
     * @param User
     * @return mixed
    */
    public static function Visiters( $month )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        # 訪問者ID
        $id_array = PointHIstory::where('reason_id',$reason_id)
        ->whereYear('created_at',$month)
        ->whereMonth('created_at',$month)
        ->pluck('user_id')->toArray();

        # 訪問者データ
        $visiters = User::find( $id_array );

        # ポイント購入金額データの追加
        foreach ($visiters as $visiter) {
            $visiter->point_price = self::VisiterPointPrice( $month, $visiter );
            $visiter->count = self::VisiterCount( $month, $visiter );
        }


        return $visiters;
    }




    /**
     * 月間の顧客のポイント購入金額
     *
     * @param Carbon\Carbon $month
     * @param App\Models\User $visiter
     * @return mixed
    */
    public static function VisiterPointPrice( $month, $visiter )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        return PointHIstory::where('reason_id',$reason_id)
        ->whereYear('created_at',$month)
        ->whereMonth('created_at',$month)
        ->where('user_id', $visiter->id)
        ->sum('price');
    }



    /**
     * 月間の顧客の購入回数
     *
     * @param Carbon\Carbon $month
     * @param App\Models\User $visiter
     * @return mixed
    */
    public static function VisiterCount( $month, $visiter )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        return PointHIstory::where('reason_id',$reason_id)
        ->whereYear('created_at',$month)
        ->whereMonth('created_at',$month)
        ->where('user_id', $visiter->id)
        ->count();
    }


}
