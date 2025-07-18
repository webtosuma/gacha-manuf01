<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Store\AdminStoreSalesReportDailyController as Daily;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
/*
| =============================================
|  ストアーAdmin　販売レポート コントローラー
| =============================================
*/
class AdminStoreSalesReportController extends Controller
{
    /**
     * 表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $day = Carbon::parse('2025-07-17');
        list( $start_day, $last_day, $days_labels ) = self::aggDays($request);

        // dd(
            // Daily::getTotalSales($day)
            # 還元ポイント(redemption_point_count)
            // list($total_redemption_point_count, $data_list['redemption_point_count'] )
            // = self::aggRedemptionPointCount( $start_day, $last_day)

        // );

        return view('store_admin.sales_report.index');
    }



    /**
     * API 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_index(Request $request)
    {
        # データリスト
        $data_list = [];

        # 開始日・終了日・日付ラベル
        list( $start_day, $last_day, $days_labels ) = self::aggDays($request);
        $start_day_format = $start_day->format('Y-m-d');
        $last_day_format  = $last_day->format('Y-m-d');
        $data_list['labels'] = $days_labels;//データリスト保存

        # 売上集計(sales)
        list($total_sales, $data_list['sales'] )
        = self::aggSales( $start_day, $last_day);

        # 客数(visiters_count)
        list($total_visiters_count, $data_list['visiters_count'] )
        = self::aggVisitersCount( $start_day, $last_day);

        # リピーター数(reprater_count)
        list($total_reprater_count, $data_list['reprater_count'] )
        = self::aggRepraterCount( $start_day, $last_day);

        # 販売回数(payment_count)
        list($total_payment_count, $data_list['payment_count'] )
        = self::aggPaymentCount( $start_day, $last_day);

        # 販売商品数(sales_prodact_count)
        list($total_sales_prodact_count, $data_list['sales_prodact_count'] )
        = self::aggSalesProdactCount( $start_day, $last_day);

        # 還元ポイント(redemption_point_count)
        list($total_redemption_point_count, $data_list['redemption_point_count'] )
        = self::aggRedemptionPointCount( $start_day, $last_day);

        # 合計値リスト
        $totals = [
            'sales'                  => ['value'=> $total_sales,                  'label'=> '売上'],        //合計売上
            'visiters_count'         => ['value'=> $total_visiters_count,         'label'=> '顧客数'],        //合計客数
            'reprater_count'         => ['value'=> $total_reprater_count,         'label'=> 'リピーター数'], //リピーター数
            'payment_count'          => ['value'=> $total_payment_count,          'label'=> '販売回数'],    //販売回数
            'sales_prodact_count'    => ['value'=> $total_sales_prodact_count,    'label'=> '販売商品数'],   //販売商品数
            'redemption_point_count' => ['value'=> $total_redemption_point_count, 'label'=> '還元ポイント'], //還元ポイント
        ];

        # 日付の種類選択
        $select_day_types = self::$selectDayTypes;

        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'start_day_format', 'last_day_format',
            'data_list','totals','select_day_types','inputs'
        ) );
    }



        /**
         *  日付の種類選択
         */
        protected static array $selectDayTypes = [
            'custom'     => 'カスタム',
            'this_month' => '今月',
            'last_month' => '先月',
            '7days'      => '過去7日間',
            '14days'     => '過去14日間',
            '28days'     => '過去28日間',
            '30days'     => '過去30日間',
        ];



        /**
         * [メソッド]開始日・終了日・日付ラベル
         *
         * @param \Illuminate\Http\Request $request
         * @return Array [ $start_day, $last_day, $days_labels]
        */
        private function aggDays($request)
        {
            # 日付カーボンの生成
            switch ($request->days_type)
            {
                case 'custom': //カスタム
                    $start_day = $request->start_day ? Carbon::parse($request->start_day) : Carbon::now()->startOfMonth();
                    $last_day  = $request->last_day  ? Carbon::parse($request->last_day)  : Carbon::now();
                    break;

                case 'this_month': //今月
                    $start_day = Carbon::now()->startOfMonth(); // 今月の1日（00:00:00）
                    $last_day  = Carbon::now()->endOfMonth();   // 今月の末日（23:59:59）
                    break;

                case 'last_month': //先月
                    $start_day = Carbon::now()->subMonth()->startOfMonth(); // 先月1日 00:00:00
                    $last_day  = Carbon::now()->subMonth()->endOfMonth();   // 先月末日 23:59:59
                    break;

                case '7days': //過去7日間
                    $last_day  = Carbon::yesterday();              // 昨日（終了日）
                    $start_day = Carbon::yesterday()->subDays(7-1);// 昨日から6日前（開始日）
                    break;

                case '14days': //過去14日間
                    $last_day  = Carbon::yesterday();              // 昨日（終了日）
                    $start_day = Carbon::yesterday()->subDays(14-1);// 昨日から6日前（開始日）
                    break;

                case '28days': //過去28日間
                    $last_day  = Carbon::yesterday();              // 昨日（終了日）
                    $start_day = Carbon::yesterday()->subDays(28-1);// 昨日から6日前（開始日）
                    break;

                case '30days': //過去30日間
                    $last_day  = Carbon::yesterday();              // 昨日（終了日）
                    $start_day = Carbon::yesterday()->subDays(30-1);// 昨日から6日前（開始日）
                    break;

                default:

                    $start_day   =  Carbon::parse($request->days_type)->startOfMonth();
                    $last_day    =  Carbon::parse($request->days_type)->endOfMonth();
                    break;
            }


            # 日付ラベル配列を作成
            $days_labels = [];
            $period = CarbonPeriod::create($start_day, $last_day);// 日付期間を作成
            foreach ($period as $day) {
                $days_labels[] = $day->format('m/d');
            }

            return [ $start_day, $last_day, $days_labels];
        }



        /**
         * [メソッド] 売上集計(sales)
        */
        private function aggSales( $start_day, $last_day)
        {
            $days = [];
            $total = 0;

            $period = CarbonPeriod::create($start_day, $last_day);// 日付期間を作成
            foreach ($period as $day) {
                $daily_val = Daily::getTotalSales($day);
                $days[]    = $daily_val;
                $total    += $daily_val;
            }

            return [ $total, $days];
        }



        /**
         * [メソッド] 客数集計(visiters_count)
        */
        private function aggVisitersCount( $start_day, $last_day)
        {
            $days = [];
            $total = 0;

            $period = CarbonPeriod::create($start_day, $last_day);// 日付期間を作成
            foreach ($period as $day) {
                $daily_val = Daily::getVisitersCount($day);
                $days[]    = $daily_val;
                $total    += $daily_val;
            }

            return [ $total, $days];
        }



        /**
         * [メソッド] リピーター数集計(reprater_count)
        */
        private function aggRepraterCount( $start_day, $last_day)
        {
            $days = [];
            $total = 0;

            $period = CarbonPeriod::create($start_day, $last_day);// 日付期間を作成
            foreach ($period as $day) {
                $daily_val = Daily::getRepraterCount($day);
                $days[]    = $daily_val;
                // $total    += $daily_val;
            }

            # 総合リピーター数
            $total = User::whereHas('store_histories', function ($query) use ($start_day, $last_day) {
                $query->whereNotNull('done_at')
                    ->whereBetween('done_at', [
                        $start_day->startOfDay(),
                        $last_day->endOfDay()
                    ]);
            }, '>=', 2)->count();

            return [ $total, $days];
        }



        /**
         * [メソッド] 販売回数集計(payment_count)
        */
        private function aggPaymentCount( $start_day, $last_day)
        {
            $days = [];
            $total = 0;

            $period = CarbonPeriod::create($start_day, $last_day);// 日付期間を作成
            foreach ($period as $day) {
                $daily_val = Daily::getPaymentCount($day);
                $days[]    = $daily_val;
                $total    += $daily_val;
            }

            return [ $total, $days];
        }



        /**
         * [メソッド] 販売商品数(sales_prodact_count)
        */
        private function aggSalesProdactCount( $start_day, $last_day)
        {
            $days = [];
            $total = 0;

            $period = CarbonPeriod::create($start_day, $last_day);// 日付期間を作成
            foreach ($period as $day) {
                $daily_val = Daily::getSalesProdactCount($day);
                $days[]    = $daily_val;
                $total    += $daily_val;
            }

            return [ $total, $days];
        }



        /**
         * [メソッド] 販売商品数(redemption_point_count)
        */
        private function aggRedemptionPointCount( $start_day, $last_day)
        {
            $days = [];
            $total = 0;

            $period = CarbonPeriod::create($start_day, $last_day);// 日付期間を作成
            foreach ($period as $day) {
                $daily_val = Daily::getRedemptionPointCount($day);
                $days[]    = $daily_val;
                $total    += $daily_val;
            }

            return [ $total, $days];
        }

    /*  */
}
