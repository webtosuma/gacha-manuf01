<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointHistory;
use App\Models\User;
use App\Models\UserGachaHistory;
use Carbon\Carbon;
/*
| =============================================
|  Admin　日別　ポイント売上レポート(改正版) コントローラー
| =============================================
*/
class AdminPointSalesReportDailyController extends Controller
{
    /**
     * 曜日配列
     */
    protected static array $weeks = ['(日)','(月)','(火)','(水)','(木)','(金)','(土)'];

    /**
     * 表示
     */
    public function index($date_format)
    {
        return view('admin.point_sales_report.daily', compact('date_format'));
    }

    /**
     * API（日別）
     */
    public function api_index(Request $request, $date_format)
    {
        // =========================
        // 日付生成
        // =========================
        $day = Carbon::parse($date_format);

        $start = $day->copy()->startOfDay();
        $end   = $day->copy()->endOfDay();

        $day_format = $day->format('Y年m月d日') . self::$weeks[$day->format('w')];

        // =========================
        // 一覧データ（N+1回避）
        // =========================
        $data_list = PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [$start, $end])
            ->with('user') // ← N+1防止
            ->orderByDesc('created_at')
            ->paginate(20);

        // フロント用整形
        $data_list->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,

                'value' => (int) $item->value, // ← 追加（これが原因）
                'price' => (int) $item->price,

                'reason_id' => $item->reason_id,

                'created_at_format' => $item->created_at->format('H:i'),

                'user' => [
                    'id' => $item->user->id ?? null,
                    'name' => $item->user->name ?? '',
                ],

                'ra_user_show' => route('admin.user.show', $item->user),
                'ra_user_point_history' => route('admin.user.point_history', [
                    'user_id' => $item->user->id ?? null,
                    'reason_id' => 11,
                ]),
            ];
        });

        // =========================
        // 合計（すべて単発クエリ）
        // =========================

        // 売上
        $sales = (int) PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [$start, $end])
            ->sum('price');

        // 客数（ユニーク）
        $visiters = (int) PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [$start, $end])
            ->distinct('user_id')
            ->count('user_id');

        // リピーター（2回以上）
        $repeaters = (int) PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('user_id, COUNT(*) as cnt')
            ->groupBy('user_id')
            ->having('cnt', '>=', 2)
            ->get()
            ->count();

        // 購入回数
        $payments = (int) PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // ガチャ回転数
        $gacha = (int) UserGachaHistory::whereBetween('created_at', [$start, $end])
            ->sum('play_count');

        // =========================
        // レスポンス
        // =========================
        return response()->json([
            'day_format' => $day_format,
            'data_list' => $data_list,

            'totals' => [
                'sales' => ['value' => $sales, 'label' => '売上'],
                'visiters_count' => ['value' => $visiters, 'label' => '顧客数'],
                'reprater_count' => ['value' => $repeaters, 'label' => 'リピーター数'],
                'payment_count' => ['value' => $payments, 'label' => '販売回数'],
                'redemption_point_count' => ['value' => $gacha, 'label' => 'ガチャ回転数'],
            ],

            'inputs' => $request->all(),
        ]);
    }
}
