<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\AdminPointSalesReportController as Report;
use Carbon\Carbon;
/*
| =============================================
|  Admin　ポイント売上レポート[顧客一覧] コントローラー
| =============================================
*/
class AdminPointSalesReportVisitersController extends Controller
{
    /**
     * API 顧客一覧
     */
    public function api_index(Request $request)
    {
        // =========================
        // 日付取得
        // =========================
        [$start_day, $last_day] = Report::aggDays($request);

        $start = $start_day->copy()->startOfDay();
        $end   = $last_day->copy()->endOfDay();

        // =========================
        // クエリ構築（N+1完全排除）
        // =========================
        $query = User::query();

        // 購入ユーザーのみ
        $query->whereHas('point_histories', function ($q) use ($start, $end) {
            $q->adominPointHistoryReason()
              ->whereBetween('created_at', [$start, $end]);
        });

        // 購入回数
        $query->withCount([
            'point_histories as sales_count' => function ($q) use ($start, $end) {
                $q->adominPointHistoryReason()
                  ->whereBetween('created_at', [$start, $end]);
            }
        ]);

        // 購入金額
        $query->withSum([
            'point_histories as total_price' => function ($q) use ($start, $end) {
                $q->adominPointHistoryReason()
                  ->whereBetween('created_at', [$start, $end]);
            }
        ], 'price');

        // 並び順（重要）
        $query->orderByDesc('total_price');

        // =========================
        // データ取得
        // =========================
        $visiters = $query->get();

        // =========================
        // フロント用整形
        // =========================
        $visiters->transform(function ($user) {

            return [
                'id' => $user->id,
                'name' => $user->name,

                // null対策
                'total_price' => (int) ($user->total_price ?? 0),
                'sales_count' => (int) ($user->sales_count ?? 0),

                // ルーティング
                'ra_user_show' => route('admin.user.show', $user),
            ];
        });

        // =========================
        // レスポンス
        // =========================
        return response()->json([
            'visiters' => $visiters,
            'inputs' => $request->all(),
        ]);
    }
}
