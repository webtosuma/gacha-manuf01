<?php

namespace App\Services;

use App\Models\PointHistory;
use App\Models\UserGachaHistory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
/*
| =============================================
|  ポイント売上レポート(改正版) サービス
| =============================================
*/
class PointSalesReportService
{
    /**
     * レポート取得
     *
     * @param Carbon $start_day
     * @param Carbon $last_day
     * @return array
     */
    public function getReport(Carbon $start_day, Carbon $last_day): array
    {
        // 日付期間
        $period = CarbonPeriod::create($start_day, $last_day);

        // 日付キー（Y-m-d）
        $dateKeys = collect($period)->map(function ($d) {
            return $d->format('Y-m-d');
        });

        // =========================
        // 売上
        // =========================
        $sales = PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [
                $start_day->copy()->startOfDay(),
                $last_day->copy()->endOfDay()
            ])
            ->selectRaw('DATE(created_at) as date, SUM(price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // =========================
        // 客数（ユニークユーザー）
        // =========================
        $visiters = PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [
                $start_day->copy()->startOfDay(),
                $last_day->copy()->endOfDay()
            ])
            ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT user_id) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // =========================
        // 販売回数
        // =========================
        $payments = PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [
                $start_day->copy()->startOfDay(),
                $last_day->copy()->endOfDay()
            ])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // =========================
        // ガチャ回転数
        // =========================
        $gacha = UserGachaHistory::whereBetween('created_at', [
                $start_day->copy()->startOfDay(),
                $last_day->copy()->endOfDay()
            ])
            ->selectRaw('DATE(created_at) as date, SUM(play_count) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // =========================
        // リピーター（1日内で2回以上購入）
        // =========================
        $repeatRaw = PointHistory::adominPointHistoryReason()
            ->whereBetween('created_at', [
                $start_day->copy()->startOfDay(),
                $last_day->copy()->endOfDay()
            ])
            ->selectRaw('DATE(created_at) as date, user_id, COUNT(*) as cnt')
            ->groupBy('date', 'user_id')
            ->get()
            ->groupBy('date');

        $repeaters = $repeatRaw->map(function ($users) {
            return $users->where('cnt', '>=', 2)->count();
        });

        // =========================
        // データ整形（抜け日付補完）
        // =========================
        $format = function (Collection $collection) use ($dateKeys) {
            return $dateKeys->map(function ($date) use ($collection) {
                return (int) ($collection[$date] ?? 0);
            })->toArray();
        };

        return [
            'sales' => $format($sales),
            'visiters' => $format($visiters),
            'payments' => $format($payments),
            'gacha' => $format($gacha),
            'repeaters' => $format($repeaters),

            'totals' => [
                'sales' => (int) $sales->sum(),
                'visiters' => (int) $visiters->sum(),
                'payments' => (int) $payments->sum(),
                'gacha' => (int) $gacha->sum(),
                'repeaters' => (int) $repeaters->sum(),
            ]
        ];
    }
}
