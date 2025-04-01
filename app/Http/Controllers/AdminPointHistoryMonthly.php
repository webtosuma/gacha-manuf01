<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointHistory;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserGachaHistory;

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
            $visiters = Daily::Visiters($date);

            $day_reports[] = [
                'date_text'=> $date->format('Y-m-d'),
                'date'     => $date->copy(),
                'sales'    => Daily::Sales($date),    //日別の売上
                'visiters' => $visiters,              //日別の顧客レポート
                'repeater_count'  => Daily::RepeaterCount( $visiters, $date ),// 日別のリピーター数
                'payment_count'   => Daily::PaymentCount( $date ),   // 日別の購入数
                'gacha_play_count'=> Daily::GachaPlayCount( $date ), // 日別のガチャ回転数

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


        # データの抽出
        $query = PointHIstory::query();

            if( $month )
            {
                $query->whereYear('created_at',$month);
                $query->whereMonth('created_at',$month);
            }

        return $query->adominPointHistoryReason()->sum('price');
    }




    /**
     * 月間の顧客レポート
     *
     * @param Carbon\Carbon $month
     * @param User
     * @return mixed
    */
    public static function Visiters( $month, $sortByKey )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        # 訪問者ID
        $query = PointHIstory::query();
        if( $month )
        {
            $query->whereYear('created_at',$month);
            $query->whereMonth('created_at',$month);
        }
        $id_array = $query->adominPointHistoryReason()
        ->pluck('user_id')->toArray();


        # 訪問者データ
        $visiters_model = User::orderByDesc('created_at')
        ->orderByDesc('id')
        ->whereIn('id',$id_array)->get();


        # ポイント購入金額データの追加
        foreach ($visiters_model as $visiter) {
            $visiter->point_price = self::VisiterPointPrice( $month, $visiter );
            $visiter->count       = self::VisiterCount( $month, $visiter );
        }


        # 並べ替え
        $visiters=[];
        foreach($visiters_model as $visiter) { $visiters[] = $visiter; }

        // $sortByKey = 'point_price';
        if($sortByKey)
        {
            uasort($visiters, function ($a, $b) use ($sortByKey) {
                // return $a[$sortByKey] - $b[$sortByKey]; //照準
                return $b[$sortByKey] - $a[$sortByKey];//降順
            });
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

        $query = PointHIstory::query();

            if( $month )
            {
                $query->whereYear('created_at',$month);
                $query->whereMonth('created_at',$month);
            }

            $query->adominPointHistoryReason();//入出ID絞り込み スコープ

        return $query->where('user_id', $visiter->id)
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

        $query = PointHIstory::query();

            if( $month )
            {
                $query->whereYear('created_at',$month);
                $query->whereMonth('created_at',$month);
            }

            $query->adominPointHistoryReason();//入出ID絞り込み スコープ

        return $query->where('user_id', $visiter->id)
        ->count();
    }



    /**
     * 月間のリピーター数
     *
     * @param User $visiters
     * @param App\Models\User $visiter
     * @return mixed
    */
    public static function RepeaterCount( $visiters, $month )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();

        $count = 0;
        foreach ($visiters as $visiter) {

            $query = PointHIstory::query();

                if( $month )
                {
                    $next_month = $month->copy()->addMonth();
                    $query->where('created_at','<',$next_month->format('y-m-01'));
                }

                $query->adominPointHistoryReason();//入出ID絞り込み スコープ

                $query->where('user_id', $visiter->id);

            $visiter_count = $query->get()->count();


            if( $visiter_count >1 ){ $count ++; }
        }
        return $count;
    }




    /**
     * 月間の購入数
     *
     * @param Carbon\Carbon $month
     * @param App\Models\User $visiter
     * @return mixed
    */
    public static function PaymentCount( $month )
    {
        # ポイントの入出理由ID (ポイント購入)
        $reason_id = self::ReasonId();


        # データ抽出
        $query = PointHistory::query();

            if( $month )
            {
                $query->whereYear('created_at',$month);
                $query->whereMonth('created_at',$month);
            }
            $query->adominPointHistoryReason();//入出ID絞り込み スコープ


        return $query->get()->count();
    }



    /**
     * 月間のガチャ回転数
     *
     * @param Carbon\Carbon $month
     * @param App\Models\User $visiter
    */
    public static function GachaPlayCount( $month )
    {
        # データ抽出
        $query = UserGachaHistory::query();

            if( $month )
            {
                $query->whereYear('created_at',$month);
                $query->whereMonth('created_at',$month);
            }

        return $query->sum('play_count');
    }
}
