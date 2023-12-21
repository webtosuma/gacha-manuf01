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
     * @param String $month_text
     * @return \Illuminate\Http\Response
     */
    public function index( $month_text=null )
    {
        # 指定月のオブジェクト
        $month_text = $month_text ?? now()->format('Y-m-01');
        $month = Carbon::parse($month_text);


        # グラフ用データ

        # パラメーター
        $params = [

            'chart'  => self::Chart($month),//グラフ用データ(文字列データの配列)

            'day_reports' => Monthly::DayReports($month),// 月間の日別レポート
            'sales'       => Monthly::Sales($month),      // 月間の合計売上
            'visiters'    => Monthly::Visiters($month),   // 月間の顧客レポート

            'this_month'     => $month,// 今月
            'next_month'     => $month->copy()->addMonth(),// 翌月
            'previous_month' => $month->copy()->subMonth(),// 先月
            'all_months'     => self::AllMonths(),// 表示可能な全ての月

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


}
