<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PointSalesReportService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
/*
| =============================================
|  Admin　ポイント売上レポート(改正版) コントローラー
| =============================================
*/
class AdminPointSalesReportController extends Controller
{
    /**
     * 日付の種類選択
     */
    protected static array $selectDayTypes = [
        'custom' => 'カスタム',
        'this_month' => '今月',
        'last_month' => '先月',
        '7days' => '過去7日間',
        '14days' => '過去14日間',
        '28days' => '過去28日間',
        '30days' => '過去30日間',
    ];

    /**
     * 曜日配列
     */
    protected static array $weeks = ['(日)','(月)','(火)','(水)','(木)','(金)','(土)'];

    /**
     * 一覧画面
     */
    public function index(Request $request)
    {
        return view('admin.point_sales_report.index');
    }

    /**
     * API（一覧）
     */
    public function api_index(Request $request, PointSalesReportService $service)
    {
        // =========================
        // 日付取得
        // =========================
        [$start_day, $last_day] = self::aggDays($request);

        // =========================
        // サービスからデータ取得
        // =========================
        $report = $service->getReport($start_day, $last_day);

        // =========================
        // ラベル生成
        // =========================
        $period = CarbonPeriod::create($start_day, $last_day);

        $labels = [];
        $w_labels = [];
        $r_daily_array = [];

        foreach ($period as $day) {
            $labels[] = $day->format('m/d');
            $w_labels[] = self::$weeks[$day->format('w')];
            $r_daily_array[] = route(
                'admin.point_sales_report.daily',
                $day->format('Y-m-d')
            );
        }

        // =========================
        // レスポンス
        // =========================
        return response()->json([
            'start_day_format' => $start_day->format('Y-m-d'),
            'last_day_format' => $last_day->format('Y-m-d'),

            'data_list' => [
                'labels' => $labels,
                'w_labels' => $w_labels,
                'r_daily_array' => $r_daily_array,

                // Vueとキーを合わせる
                'sales' => $report['sales'],
                'visiters_count' => $report['visiters'],
                'reprater_count' => $report['repeaters'],
                'payment_count' => $report['payments'],
                'gacha_played_count' => $report['gacha'],
            ],

            'totals' => [
                'sales' => [
                    'value' => $report['totals']['sales'],
                    'label' => '売上'
                ],
                'visiters_count' => [
                    'value' => $report['totals']['visiters'],
                    'label' => '顧客数'
                ],
                'reprater_count' => [
                    'value' => $report['totals']['repeaters'],
                    'label' => 'リピーター数'
                ],
                'payment_count' => [
                    'value' => $report['totals']['payments'],
                    'label' => '販売回数'
                ],
                'gacha_played_count' => [
                    'value' => $report['totals']['gacha'],
                    'label' => 'ガチャ回転数'
                ],
            ],

            'select_day_types' => self::$selectDayTypes,

            // 顧客API
            'r_api_visiters' => route('admin.api.point_sales_report.visiters'),

            'inputs' => $request->all(),
        ]);
    }

    /**
     * 日付範囲生成
     */
    public static function aggDays($request): array
    {
        switch ($request->days_type) {

            case 'custom':
                $start_day = $request->start_day
                    ? Carbon::parse($request->start_day)
                    : Carbon::now()->startOfMonth();

                $last_day = $request->last_day
                    ? Carbon::parse($request->last_day)
                    : Carbon::now();

                break;

            case 'this_month':
                $start_day = Carbon::now()->startOfMonth();
                $last_day = Carbon::now()->endOfMonth();
                break;

            case 'last_month':
                $start_day = Carbon::now()->subMonth()->startOfMonth();
                $last_day = Carbon::now()->subMonth()->endOfMonth();
                break;

            case '7days':
                $last_day = Carbon::yesterday();
                $start_day = Carbon::yesterday()->subDays(6);
                break;

            case '14days':
                $last_day = Carbon::yesterday();
                $start_day = Carbon::yesterday()->subDays(13);
                break;

            case '28days':
                $last_day = Carbon::yesterday();
                $start_day = Carbon::yesterday()->subDays(27);
                break;

            case '30days':
                $last_day = Carbon::yesterday();
                $start_day = Carbon::yesterday()->subDays(29);
                break;

            default:
                $start_day = Carbon::parse($request->days_type)->startOfMonth();
                $last_day = Carbon::parse($request->days_type)->endOfMonth();
                break;
        }

        return [$start_day, $last_day];
    }
}
