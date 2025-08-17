<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointHistory;
use App\Models\User;
use App\Models\UserGachaHistory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
/*
| =============================================
|  ストアーAdmin　日別　ポイント売上レポート(改正版) コントローラー
| =============================================
*/
class AdminPointSalesReportDailyController extends Controller
{
    /**
     * 表示
     *
     * @param  String $date_format
     * @return \Illuminate\Http\Response
     */
    public function index($date_format)
    {
        return view('admin.point_sales_report.daily', compact('date_format'));
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
        $data_list = PointHistory::adominPointHistoryReason()//入出ID絞り込み スコープ
        ->whereDate('created_at', $day)
        ->with('user')
        ->paginate(20);

        foreach ($data_list as $data) {
            $data->created_at_format = $data->created_at->format('H:i');//日付フォーマット：登録日
            $data->ra_user_show = route('admin.user.show',$data->user);             //[Adminルーティング]ユーザー詳細
            $data->ra_user_point_history
            = route('admin.user.point_history',['user_id'=>$data->user->id,'reason_id'=>11,]); //[Adminルーティング]ポイント履歴
        }


        # 合計値リスト
        $totals = [
            'sales'                  => ['value'=> self::getTotalSales($day),       'label'=> '売上'],        //合計売上
            'visiters_count'         => ['value'=> self::getVisitersCount($day),    'label'=> '顧客数'],        //合計客数
            'reprater_count'         => ['value'=> self::getRepraterCount($day),    'label'=> 'リピーター数'], //リピーター数
            'payment_count'          => ['value'=> self::getPaymentCount($day),     'label'=> '販売回数'],    //販売回数
            'redemption_point_count' => ['value'=> self::getGachaPlayedCount($day), 'label'=> 'ガチャ回転数'], //ガチャ回転数
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
            return (Int) PointHIstory::adominPointHistoryReason()//入出ID絞り込み スコープ
            ->whereDate('created_at',$day)
            ->sum('price');
        }



        /**
         * [メソッド]日別合計　客数(visiters_count)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getVisitersCount($day)
        {
            return (Int) PointHIstory::adominPointHistoryReason()//入出ID絞り込み スコープ
            ->whereDate('created_at',$day)
            ->distinct('user_id')//重複除去
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
            return User::whereHas('point_histories', function ($query) use ($day) {
                $query->adominPointHistoryReason()//入出ID絞り込み スコープ
                ->whereDate('created_at',$day);
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
            return (Int) PointHIstory::adominPointHistoryReason()//入出ID絞り込み スコープ
            ->whereDate('created_at',$day)
            ->count();
        }



        /**
         * [メソッド]日別合計　ガチャ回転数(sales_product_count)
         *
         * @param Carbon $day
         * @return Array
        */
        public static function getGachaPlayedCount($day)
        {
            return (Int) UserGachaHistory::whereDate('created_at',$day)
            ->sum('play_count');
        }
}
