<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointHistory;
use Carbon\Carbon;

use App\Http\Controllers\AdminPointHistoryMonthly as Monthly;
use App\Http\Controllers\AdminPointHistoryDaily   as Daily;
/*
| =============================================
|  サイト管理者 ポイント売上 コントローラー
| =============================================
*/
class AdminPointHistoryController extends Controller
{
    /**
     * 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @param String $month_text
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, $month_text=null )
    {
        // dd($request->all());


        # 指定月のオブジェクト
        $month_text = $month_text ?? now()->format('Y-m-01');
        $month = Carbon::parse($month_text);

        # 月の顧客レポートの並び替えキー
        $sort_by_key = $request->sort_by_key;

        # 月間の顧客レポート
        $visiters = Monthly::Visiters($month, $sort_by_key);


        # パラメーター
        $params = [

            'chart'  => self::Chart($month),//グラフ用データ(文字列データの配列)
            'day_reports'    => Monthly::DayReports($month),        // 月間の日別レポート


            'sales'           => Monthly::Sales($month),             // 月間の合計売上
            'visiters'        => $visiters,// 月間の顧客レポート
            'repeater_count'  => Monthly::RepeaterCount( $visiters, $month ),// 月間のリピーター数
            'payment_count'   => Monthly::PaymentCount( $month ),    // 月間の購入数
            'gacha_play_count'=> Monthly::GachaPlayCount( $month ),    // 月間のガチャ回転数


            'this_month'     => $month,// 今月
            'next_month'     => $month->copy()->addMonth(),// 翌月
            'previous_month' => $month->copy()->subMonth(),// 先月
            'all_months'     => self::AllMonths(),// 表示可能な全ての月

            'table' => $request->table ? $request->table : 'daily_report'
        ];

        return view('admin.point_history.index', $params );
    }




    /** グラフ用データ(文字列データの配列) */
    public function Chart($month)
    {
        # 月間の日別レポート
        $day_reports = Monthly::DayReports($month);

        # 日付ラベルの作成
        $labels = []; $data = [];
        foreach ($day_reports as $report) {

            $labels[] = sprintf('%02d', $report['date']->day );
            $data []   = (int) $report['sales'];
        }

        return [
            'labels' => implode( ',', $labels ),
            'data'   => implode( ',', $data ),
        ];
    }



    /** 表示可能な全ての月 */
    public function AllMonths()
    {
        # 最も古い月(＊ポイント購入のみ)
        $month = Carbon::parse( now()->format('Y-m-01') );
        $first_point_history =  PointHistory::where('reason_id',11)->first();
        $old_month = $first_point_history ? $first_point_history->created_at : $month->copy();

        $array = [];
        while ($month->format('Y-m') >= $old_month->format('Y-m')) {


            $array[] = $month->copy()->format('Y-m-01');
            $month->subMonth();
        }

        return $array;
    }






    /**
     * 時系列一覧
     * @param \Illuminate\Http\Request $request
     * @param String $month_text
     * @return \Illuminate\Http\Response
     */
    public function datetime(Request $request)
    {
        $point_histories =  PointHistory::where('reason_id',11)//購入履歴
        ->orderByDesc('created_at')
        ->paginate(100);//ページネーション


        # 月間の顧客レポート
        $visiters = Monthly::Visiters(null, null);


        $params = [

            'point_histories'  => $point_histories,

            'sales'           => Monthly::Sales( null ),             // 通算の合計売上
            'visiters'        => $visiters,// 通算の顧客レポート
            'repeater_count'  => Monthly::RepeaterCount( $visiters, null ),// 通算のリピーター数
            'payment_count'   => Monthly::PaymentCount( null ),    // 通算の購入数
            'gacha_play_count'=> Monthly::GachaPlayCount( null ),  // 通算のガチャ回転数
        ];

        return view('admin.point_history.datetime', $params );
    }





    /**
     * 日別レポート
     *
     * @param String $date_text
     * @return \Illuminate\Http\Response
     */
    public function daily( $date_text )
    {
        # 指定日のオブジェクト
        $date = Carbon::parse($date_text);

        // dd();
        $point_histories =  PointHistory::where('reason_id',11)//購入履歴
        ->whereDate('created_at',$date)
        ->orderByDesc('created_at')
        ->paginate(100);//ページネーション

        $visiters = Daily::Visiters($date);

        $params = [

            'date' => $date,
            'point_histories'  => $point_histories,

            'sales'    => Daily::Sales($date),    //日別の売上
            'visiters' => $visiters,              //日別の顧客レポート
            'repeater_count'  => Daily::RepeaterCount( $visiters, $date ),// 日別のリピーター数
            'payment_count'   => Daily::PaymentCount( $date ),   // 日別の購入数
            'gacha_play_count'=> Daily::GachaPlayCount( $date ), // 日別のガチャ回転数
        ];


        return view('admin.point_history.daily', $params );
    }
}
