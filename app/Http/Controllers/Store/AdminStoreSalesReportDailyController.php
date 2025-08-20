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
     * @param String $date_format
     * @return \Illuminate\Http\Response
    */
    public function api_index( Request $request, $date_format )
    {

        # 日付カーボン
        $day = Carbon::parse( $date_format );
        $day_format = $day->format('Y年m月d日').self::$weeks[$day->format('w')];

        # 売上レポート
        $data_list = StoreHistory::whereNotNull('done_at')
        ->whereDate('done_at', $day->toDateString())
        // ->ordrByDesc('done_at')
        ->paginate(20);

        foreach ($data_list as $data) {
            $data->sum_count             = $data->sumItemsCount();//点数
            $data->sum_points_redemption = $data->sumItemsPointsRedemption();//還元ポイント
            $data->total_price           = $data->totalItemsPrice();//請求金額

            $data->ra_user_show = route('admin.user.show',$data->user);             //[Adminルーティング]ユーザー詳細
            $data->ra_store_shipped_show = route('admin.store.shipped.show',$data); //[Adminルーティング]発送詳細
        }


        # 合計値リスト
        $totals = [
            'sales'                  => ['value'=> self::getTotalSales($day),           'label'=> '売上'],        //合計売上
            'visiters_count'         => ['value'=> self::getVisitersCount($day),        'label'=> '顧客数'],        //合計客数
            'reprater_count'         => ['value'=> self::getRepraterCount($day),        'label'=> 'リピーター数'], //リピーター数
            'payment_count'          => ['value'=> self::getPaymentCount($day),         'label'=> '販売回数'],    //販売回数
            'sales_product_count'    => ['value'=> self::getSalesProductCount($day),    'label'=> '販売商品数'],   //販売商品数
            'redemption_point_count' => ['value'=> self::getRedemptionPointCount($day), 'label'=> '還元ポイント'], //還元ポイント
        ];

        # 入力値
        $inputs = $request->all();

        return response()->json( compact(
            'day_format','data_list','totals',
            'inputs'
        ) );
    }



        /**
         *  曜日配列
         */
        protected static array $weeks = ['(日)','(月)','(火)','(水)','(木)','(金)','(土)',];



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
         * [メソッド]日別合計　販売商品数(sales_product_count)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getSalesProductCount($day)
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
