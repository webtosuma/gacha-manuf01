<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\StoreHistory;
use App\Models\StoreKeep;
use App\Models\User;
/*
| =============================================
|  ストアーAdmin　日別　販売レポート コントローラー
| =============================================
*/
class AdminStoreSalesReportDailyController extends Controller
{
    /**
     * 表示
     *
     * @param  String $date_format
     * @return \Illuminate\Http\Response
     */
    public function index($date_format)
    {
        return view('store_admin.sales_report.daily', compact('date_format'));
    }



    /**
     * API 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_index(Request $request)
    {
        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'inputs'
        ) );
    }




        /**
         * [メソッド]日別合計　売上(sales)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getTotalSales($day)
        {
            # 小計の合計
            $totalDonePrice = StoreKeep::whereHas('store_history', function ($query) use ($day) {
                $query->whereNotNull('done_at')
                    ->whereDate('done_at', $day->toDateString());
            })->sum('done_sum_price');

            # 発送料金の合計
            $totalShippedPrice = StoreHistory::whereNotNull('done_at')
            ->whereDate('done_at', $day->toDateString())
            ->sum('shipped_price');

            # 利用ポイントの合計
            $totalUsePoint = StoreHistory::whereNotNull('done_at')
            ->whereDate('done_at', $day->toDateString())
            ->sum('use_point');


            return  (Int) ($totalDonePrice + $totalShippedPrice - $totalUsePoint);
        }



        /**
         * [メソッド]日別合計　客数(visiters_count)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getVisitersCount($day)
        {
            return StoreHistory::whereNotNull('done_at')
            ->whereDate('done_at', $day->toDateString())
            ->distinct('user_id')
            ->count('user_id');
        }



        /**
         * [メソッド]日別合計　リピーター数(reprater_count)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getRepraterCount($day)
        {
            return User::whereHas('store_histories', function ($query) use ($day) {
                $query->whereNotNull('done_at')
                ->whereDate('done_at', $day->toDateString());
            }, '>=', 2) // ← ここで2件以上の履歴を持つユーザーに絞る
            ->count();
        }



        /**
         * [メソッド]日別合計　販売回数(payment_count)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getPaymentCount($day)
        {
            return StoreHistory::whereNotNull('done_at')
            ->whereDate('done_at', $day->toDateString()) // 日付が同じ
            ->count();
        }



        /**
         * [メソッド]日別合計　販売商品数(sales_prodact_count)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getSalesProdactCount($day)
        {
            return (Int) StoreKeep::whereHas('store_history', function ($query) use ($day) {
                $query->whereNotNull('done_at')
                ->whereDate('done_at', $day->toDateString());
            })->sum('count');
        }



        /**
         * [メソッド]日別合計　還元ポイント(redemption_point_count)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getRedemptionPointCount($day)
        {
            return (Int) StoreHistory::whereNotNull('done_at')
            ->whereDate('done_at', $day->toDateString())
            ->sum('redemption_point');
        }
    }
